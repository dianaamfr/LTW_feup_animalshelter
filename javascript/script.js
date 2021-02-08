'use strict'

// ---------- Search Pets -----------

let searchForm = document.getElementById('caracteristicas')
let filteredPets = document.getElementById('filteredPets')

if(searchForm){
    searchForm.addEventListener('submit', function(event){
        searchPets(event)

        if(filteredPets.firstChild){
            filteredPets.scrollIntoView()
        }

    })

  // Refresh Pets(Get all pets)
  refreshPets()
}

/**
 * Get search filters and make HTTP request to get search results
 * @param {*} event 
 */
function searchPets(event) {
    event.preventDefault()

    // Get search filters from input value
    let species = JSON.stringify([...document.querySelectorAll("input[name='species[]']:checked")].map(specie => specie.value))
    let gender = document.querySelector("input[name=gender]:checked").value
    let sizes = JSON.stringify([...document.querySelectorAll("input[name='sizes[]']:checked")].map(size => size.value))
    let age = document.querySelector("input[name=age]:checked").value
    let location = document.querySelector("input[name=location]").value
    let color = document.getElementById("color").value
    let breed = document.querySelector("input[name=breed]").value
    
    // Ask for the pets that fit the search filters
    let request = new XMLHttpRequest()
    request.open('post', '../api/api_search_pets.php?', true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')            
    request.addEventListener('load', filteredPetsReceived)
    request.send(encodeForAjax({'species': species,
                                'gender': gender, 
                                'sizes': sizes,
                                'age': age,
                                'location':location,
                                'color': color,
                                'breed': breed
                            }))

}

/**
 * Called when search result is received. Parse response and draw resulting pet posts or error accordingly
 */
function filteredPetsReceived(){
    
    let petPosts = JSON.parse(this.responseText)

    if(petPosts.length == 0){
        clearNode(filteredPets)
        let alert = document.createElement('span')
        alert.classList.add('error')
        alert.innerHTML = 'No pets to show'
        filteredPets.append(alert)
        return
    }

    if(petPosts.length != 0 && petPosts[0].hasOwnProperty('type') && petPosts[0].type == 'error'){
        clearNode(filteredPets)
        showErrors(petPosts, filteredPets)
        return
    }
    drawPetPosts(petPosts, filteredPets)
}

/**
 * Get all the pets by sending HTTP request 
 */
function refreshPets() {
    let request = new XMLHttpRequest()
    request.open('post', '../api/api_search_pets.php?', true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.addEventListener('load', filteredPetsReceived)
    request.send()
}


// ----------- Favourites -----------

let favourites = document.getElementById("favourite_pets") // Favourites Content

// If on the favourites page, get favourites
if(favourites){
    refreshFavourites()
}

/**
 * Get all favourites when favourites page is loaded by making HTTP request
 */
function refreshFavourites() {
    let request = new XMLHttpRequest()
    request.open('post', '../api/api_favourite.php?', true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.addEventListener('load', favouritesReceived)
    request.send()
}

/**
 * Called when favourites are received. Parse response from HTTP request. 
 * Show favourites or error message according to the response.
 */
function favouritesReceived(){

    let favs = JSON.parse(this.responseText)

    // At the favourites page
    if(favourites){
        let alert;

        // Session error
        if(!Array.isArray(favs) && favs.hasOwnProperty('type') && favs.type === 'session'){
            alert = document.createElement('span')
            alert.classList.add('error')
            alert.innerHTML = 'Please <a href="../pages/login.php">Sign In</a> or <a href="../pages/register.php">Register</a> to see your favourites'
            favourites.append(alert)
            return
        }

        // Unexpected error
        if(!Array.isArray(favs) && favs.hasOwnProperty('type') && favs.type === 'error'){
            alert = document.createElement('span')
            alert.classList.add('error')
            alert.innerHTML = 'Please <a href="../pages/login.php">Sign In</a> or <a href="../pages/register.php">Register</a> to see your favourites'
            favourites.append(alert)
            return
        }

        // No favourites defined
        if(favs.length == 0){
            alert = document.createElement('span')
            alert.classList.add('error')
            alert.innerHTML = 'You don\'t have any favourites yet!'
            favourites.append(alert)
            return
        }

        // Draw all favourites
        clearNode(favourites)
        drawPetPosts(favs, favourites)
    } 

    else if(petPage){
        favs.forEach((petPost) => {
            addFavouriteStar(petPost.petId)       
        })
    }
}

/**
 * When the star is clicked make an HTTP request 
 * @param {*} event 
 */
function toggleFavourite(event) {

    let petId = event.target.nextElementSibling.value

    let request = new XMLHttpRequest()
    request.open("post", '../api/api_favourite.php?', true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.addEventListener('load', addRemoveFavourite)
    // Send the petId of the pet post where the user clicked the star
    request.send(encodeForAjax({'petId': petId}))
}

/**
 * To be called when add/remove from favourites response is received. 
 * Delete from favourites, change star or show error according to the response
 */
function addRemoveFavourite() {
    let result = JSON.parse(this.responseText)

    if(result.type == 'add'){
        addFavouriteStar(result.petId)
    }
    else if(result.type == 'remove'){
        if(favourites){
            removeFavouritePost(result.petId)
        }
        else
            removeFavouriteStar(result.petId)
    }
}

/**
 * Draw pet posts inside parentElement
 * @param {*} petPosts 
 * @param {*} parentElement 
 */
function drawPetPosts(petPosts, parentElement){

    clearNode(parentElement)

    petPosts.forEach(function(petData){
        let petPost = document.createElement('article')                 

        petPost.classList.add('pet')
        petPost.innerHTML =
            `<header class="pet_publication">
                <section>
                    <h4>Date:</h4>
                    <p>${petData.date}</p>
                </section>

                <section>
                    <h4>Username: </h4>
                    <p>${petData.username}</p>
                </section>

                <section>
                    <h4>Location:</h4>
                    <p>${petData.location}</p>
                </section>
            </header>
            <section class="pet_content">
                <img src="../images/pets/originals/pet${petData.petId}img${petData.imageId}.jpg" alt="${petData.alternative}">
                <h3>${petData.name}</h3>  
                <form class="add_favourite" action="#" method="post">
                    <i class="far fa-star"></i>
                    <input type="hidden" name="petId" value=${petData.petId}>
                </form>
                <section class="species">
                    <h4>Species: </h4>
                    <p>${petData.species}</p>
                </section>

                <section class="breed">
                    <h4>Breed: </h4>
                    <p>${petData.breed}</p>
                </section>

                <section class="about">
                    <h4>About:</h4>
                    <p>${petData.description}</p>
                </section>

                <section class="gender">
                    <h4>Gender: </h4>
                    <p>${petData.gender}</p>
                </section>

                <section class="color">
                    <h4>Color: </h4>
                    <p>${petData.color}</p>
                </section>

                <section class="size">
                    <h4>Size: </h4>
                    <p>${petData.size}</p>
                </section>
                
                <section class="age">
                    <h4>Age: </h4>
                    <p>${petData.age}</p>
                </section>
            </section>
            
            <footer class="pet_contact">
                <a class="pet_contact_btn" href="../pages/pet_item.php?id=${petData.petId}">More</a>
            </footer>
            `
        parentElement.append(petPost)

        let postStar = document.querySelector(".add_favourite input[name='petId'][value='" + petData.petId + "']").parentElement.firstElementChild

        if(filteredPets && !petData.hasOwnProperty('isFavourite')){
            postStar.remove()
            return
        }

        if(favourites || petPage || (petData.hasOwnProperty('isFavourite') && petData.isFavourite)){
            addFavouriteStar(petData.petId) 
        } 
        
        postStar.addEventListener('click', toggleFavourite) 
    })

}

// Mark pet in pet page if it is a favourite and allow that, when a star is clicked, the favourite state will change
let petPage = document.getElementById('pet_page')
if(petPage){
    let star = document.getElementsByClassName("fa-star")[0]
    if(star){
        refreshFavourites()
        star.addEventListener('click', toggleFavourite)
    }
}

/**
 * Change star to filled - favourite
 * @param {*} petId 
 */
function addFavouriteStar(petId){
    let postId = document.querySelector(".add_favourite input[name='petId'][value='" + petId + "']")
    if(postId){
        postId.parentElement.firstElementChild.classList.add('fas')
        postId.parentElement.firstElementChild.classList.remove('far')
    }
}

/**
 * Change star to empty - not a favourite
 * @param {*} petId 
 */
function removeFavouriteStar(petId){
    let postId = document.querySelector(".add_favourite input[name='petId'][value='" + petId + "']")
    if(postId){
        postId.parentElement.firstElementChild.classList.add('far')
        postId.parentElement.firstElementChild.classList.remove('fas')
    }
}


/**
 * Remove a post that is not a favourite pet
 * @param {*} petId 
 */
function removeFavouritePost(petId){
    let postId = document.querySelector(".add_favourite input[name='petId'][value='" + petId + "']")
    if(postId){
        postId.parentElement.parentElement.parentElement.remove()

        if(!favourites.firstChild){
            let alert = document.createElement('span')
            alert.classList.add('error')
            alert.innerHTML = 'You don\'t have any favourites yet!'
            favourites.append(alert)
        }
    }

}

// ----------- Conversations & Messages -----------

// Id of last message received
let lastId = -1
let chat = document.getElementById('conversation_messages')
let error_messages = document.getElementsByClassName('messages')[0]
let new_message_form = document.getElementsByClassName('new_message')[0]
let conversationId = document.querySelector("input[name='conversationId']")

// When a message is sent update messages
if(new_message_form){
    new_message_form.addEventListener('submit', sendMessage)  
    window.setInterval(refreshMessages, 5000)
    refreshMessages()
}

/**
 * Make HTTP request to refresh messages from a conversation - get last messages
 */
function refreshMessages(){

    let request = new XMLHttpRequest()
    request.open('post', '../api/api_send_message.php?', true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.addEventListener('load', messagesReceived)
    request.send(encodeForAjax({'lastId': lastId, 'conversationId': conversationId.value}))
}

/**
 * Make HTTP request to insert a new message and refresh messages from a conversation
 */
function sendMessage(event) {
    let message = document.querySelector('input[name=message]').value
  
    // Delete sent message from input field
    document.querySelector('input[name=message]').value=''
  
    // Send message
    let request = new XMLHttpRequest()
    request.open('post', '../api/api_send_message.php?', true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.addEventListener('load', messagesReceived)
    request.send(encodeForAjax({'lastId': lastId, 'text': message, 'conversationId': conversationId.value}))
  
    event.preventDefault()
}

/**
 * Called when new messages are received. Draws new messages
 */
function messagesReceived() {
    let messages = JSON.parse(this.responseText)
   
    if(messages.length != 0 && messages[0].hasOwnProperty('type') && messages[0].type === 'error'){
        clearNode(error_messages)
        showErrors(messages, error_messages)
        return
    }

    if(messages.length > 0)
        clearNode(error_messages)

    messages.forEach(function(data){
      let message = document.createElement('div')
  
      lastId = data.messageId
  
      message.classList.add('conversation_message')
      message.innerHTML =
        '<span class="sender">' + data.sender + '</span>' +
        '<span class="conversation_message_text">' + data.messageText + '</span>' +
        '<footer><span>' + data.date + '</span></footer>'
  
      chat.append(message)
      chat.scrollTop = chat.scrollHeight
    })
}


/**
 * Encode data for Ajax
 * @param {*} data 
 */
function encodeForAjax(data) {
    return Object.keys(data).map(function(k){
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

/**
 * Delete child elements from a node
 * @param {*} node 
 */
function clearNode(node) {
    while (node.firstChild) {
        node.removeChild(node.lastChild)
    }
}

/**
 * Show error messages thrown in php apis
 * @param {*} errors 
 * @param {*} parent 
 */
function showErrors(errors, parent){
    
    errors.forEach(function(data){
       let error = document.createElement('div')
    
        error.classList.add(data['type'])
        error.innerHTML = `${data['content']}`
    
        parent.append(error)
    })
}


// ------- Pet Page Images ---------

let allPetImages = document.getElementsByClassName('pet_images')
let bigImages = document.querySelectorAll('.pet_images > img')
let myPets = document.getElementById('myPets')

if(petPage || myPets){

    Array.from(allPetImages).forEach(function(petImages) {
        let bigImg = petImages.firstElementChild
        let thumbs = Array.from(petImages.querySelectorAll('.thumbs_container img'))

        thumbs[0].addEventListener('load', function(event){
            thumbs[0].classList.add('active')
        })

        thumbs.forEach(function(thumb) {
            thumb.addEventListener('click', function(event){
                let imageId = thumb.id;
                bigImg.src = `../images/pets/originals/${imageId}.jpg`     
                bigImg.alt = thumb.alt
    
                thumbs.forEach(function(thumb){
                    thumb.classList.remove('active')
                })
    
                thumb.classList.add('active')
            })
        })
    })
    
}

// -------- Responsive Menu ---------
let menu_bars = document.getElementById('responsive_menu_icon')
let responsive_menu = document.querySelector('#responsive_menu ul')

if(window.getComputedStyle(menu_bars).display === 'block'){
    menu_bars.addEventListener('click', function (event) {
        if(window.getComputedStyle(responsive_menu).display === 'none')
            disableScroll()
        else
            enableScroll()  

        responsive_menu.classList.toggle('active')
        menu_bars.classList.toggle('fa-bars')
        menu_bars.classList.toggle('fa-times')
    });
}

/**
 * Disable scroll
 */
function disableScroll() { 
    document.body.classList.add("stop-scrolling"); 
} 

/**
 * Enable scroll
 */
function enableScroll() { 
    document.body.classList.remove("stop-scrolling"); 
} 


// --------- Pending Proposals Notifications ----------
let lastProposal = -1;
let proposalsCount = 0;
let proposals_pending = Array.from(document.getElementsByClassName('proposals_pending'))

// When a new proposal is received update number of proposals
if(proposals_pending){
    refreshProposals()
    window.setInterval(refreshProposals, 5000)
}

/**
 * Refresh Proposals making HTTP request
 */
function refreshProposals(){
    let request = new XMLHttpRequest()
    request.open('post', '../api/api_pending_proposals.php?', true)
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    request.addEventListener('load', pendingProposalsReceived)
    request.send(encodeForAjax({'lastProposal': lastProposal}))
}

/**
 * Parse received pending proposals and update proposals counter
 */
function pendingProposalsReceived(){
    let proposals = JSON.parse(this.responseText)
    
    if(Array.isArray(proposals) && proposals.length != 0){
        proposals.forEach( function(proposal){
            lastProposal = proposal.proposalId
            proposalsCount++
            proposals_pending.forEach(counter => counter.innerHTML = proposalsCount)
        })
    }

    if(proposalsCount == 0){
        proposals_pending.forEach(counter => counter.style.display = "none")
    }
    else {
        proposals_pending.forEach(counter => counter.style.display = "inline-block")
    }
}


// Delete Pet Post
let delete_pet = document.querySelector('input[name=delete_pet_btn]')
if(delete_pet) {
    delete_pet.addEventListener('click', function(event){
        let answer = confirm("Are you sure you want to delete this pet?");

        if(!answer) event.preventDefault();
    })
}