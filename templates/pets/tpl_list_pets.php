<?php
/**
 * Draw pet posts
 * @param pets The pets to draw
 */
function drawPets($pets){
    if (empty($pets)) { ?> 
        <span class="error">You have not found any pets yet.</span>  
    <?php }
    foreach($pets as $pet){
        $petImages = getPetImagesByPetId($pet['petId']);
        drawPetSimple($pet, $petImages);     
    }       
} ?>

<?php
/**
 * Draw a pet post without proposal & contact forms
 * @param pet The pet to draw
 * @param petImages The images of the pet
 */
function drawPetSimple($pet, $petImages){ ?>
    <article class="pet">
        <?php drawPetHeader($pet);?>
        <section class="pet_content">
            <?php drawPetImages($pet, $petImages); ?>
            <h3><?=$pet['name']?></h3>
            <?php drawPetInfo($pet); ?>
        </section>
        <footer class="pet_edit"> 
            <a class="pet_edit_btn" href="../pages/edit_pet.php?id=<?=$pet['petId']?>">Edit</a>  
        </footer>
    </article>
<?php } ?>

<?php
/**
 * Draw a pet
 * @param pet The pet to draw
 * @param petImages The images of the pet
 */
function drawPet($pet, $petImages){ ?>
    <article class="pet">
        <?php drawPetHeader($pet);?>
        <section class="pet_content">
            <?php drawPetImages($pet, $petImages); ?>
            <h3><?=$pet['name']?></h3>
            <?php if(isset($_SESSION['username']) && ($_SESSION['username'] != '') && !checkIsPetOwner($_SESSION['username'], $pet['petId'])
            && isAvailable($pet['petId'])){ ?>
                <form class="add_favourite" action="#" method="post">
                    <i class="far fa-star"></i>
                    <input type="hidden" name="petId" value="<?=$pet['petId']?>"">
                </form>
            <?php } 
            drawPetInfo($pet); ?>
        </section>
        <?php 
            // If user is logged in, is not the owner of the pet and the pet is available
            if (isset($_SESSION['username']) && ($_SESSION['username'] != '') && 
            ($pet['username'] != $_SESSION['username']) && ($pet['state'] == 'available')){
                drawPetForms($pet);
            }
            if(($pet['state'] == 'adopted')){?>
                <p class="hint">This pet was already adopted!</p>
            <?php } 
            else if(isset($_SESSION['username']) && $pet['username'] == $_SESSION['username']) {?>
                 <footer class="pet_edit"> 
                    <a class="pet_edit_btn" href="../pages/edit_pet.php?id=<?=$pet['petId']?>">Edit</a>  
                </footer>
            <?php } ?>
    </article>
<?php } ?>

<?php
/**
 * Draw the proposal and contact forms
 * @param pet The pet to draw
 */
function drawPetForms($pet){ ?>
    <section id="adopt_pet_forms">
        <form id="pet_contact_form" action="../actions/action_send_message.php" method="post">
            <h3>Send a message</h3>
            <label>
                <input type="hidden" name="petId" value="<?=$pet['petId']?>">
                <textarea name="messageText" rows="8" cols="80" required></textarea>
            </label>
            <input type="submit" value="Send Message">
        </form>

        <form id="pet_propose_form" action="../actions/action_proposal.php" method="post">
            <h3>Make a proposal</h3>
            <label>
                <input type="hidden" name="petId" value="<?=$pet['petId']?>">
                <textarea name="proposalText" rows="8" cols="80" required></textarea>
            </label>
            <input type="submit" value="Propose to Adopt">
        </form>
    </section>
<?php }?>

<?php
/**
 * Draw a preview of the first pets
 * @param first_pets The pets to draw
 */
function drawPetsPreview($first_pets){ ?>
    <section id="adopt_thumbnails">
        <h2>Adopt a pet</h2>
        <?php foreach ($first_pets as $pet) { ?>
            <article class="adopt_thumbnail">
                <img src="../images/pets/originals/pet<?=$pet['petId']?>img<?=$pet['imageId']?>.jpg" alt="<?=$pet['alternative']?>">
                <h3><?=$pet['name']?></h3>
                <section class="about">
                    <h4>About</h4>
                    <p><?=htmlspecialchars($pet['description'])?></p>
                </section>
                <footer>
                    <span class="location">
                        <i class="fa fa-map-marker"></i>
                        <span><?=$pet['location']?></span>
                    </span>
                    <a class="green_btn" href="../pages/pet_item.php?id=<?=$pet['petId']?>">More</a>
                </footer>
            </article>
        <?php } ?>
    <a class="green_btn" href="../pages/adopt_pets.php"> Search Pets</a>
</section>
<?php } ?>

<?php
/**
 * Draw the header of a pet post
 * @param pet The pet to draw
 */
function drawPetHeader($pet){?>
    <header class="pet_publication">
        <section>
            <h4>Date: </h4>
            <p><?=$pet['date']?></p>
        </section>
        <section>
            <h4>Username: </h4>
            <p><?=$pet['username']?></p>
        </section>
        <section>
            <h4>Location:</h4>
            <p><?=$pet['location']?></p>
        </section>
    </header>
<?php } ?>

<?php
/**
 * Draw pet info
 * @param pet The pet to draw
 */
function drawPetInfo($pet){ ?>
    <section class="species">
        <h4>Species: </h4>
        <p><?=$pet['species']?></p>
    </section>
    <section class="breed">
        <h4>Breed: </h4>
        <p><?=$pet['breed']?></p>
    </section>
    <section class="about">
        <h4>About:</h4>
        <p><?=htmlspecialchars($pet['description'])?></p>
    </section>
    <section class="gender">
        <h4>Gender: </h4>
        <p><?=$pet['gender']?></p>
    </section>
    <section class="color">
        <h4>Color: </h4>
        <p><?=$pet['color']?></p>
    </section>
    <section class="size">
        <h4>Size: </h4>
        <p><?=$pet['size']?></p>
    </section>
    <section class="age">
        <h4>Age: </h4>
        <p><?=$pet['age']?></p>
    </section>
<?php } ?>

<?php 
/**
 * Draw pet images
 * @param pet The pet to draw
 * @param petImages The images of the pet
 */
function drawPetImages($pet, $petImages){?>
    <div class="pet_images">
        <img src="../images/pets/originals/pet<?=$pet['petId']?>img<?=$petImages[0]['imageId']?>.jpg" alt="<?=$petImages[0]['alternative']?>">
        <div class="thumbs_container">
            <?php foreach($petImages as $petImage){ ?>
                <img id="pet<?=$pet['petId']?>img<?=$petImage['imageId']?>" src="../images/pets/thumbs/pet<?=$pet['petId']?>img<?=$petImage['imageId']?>.jpg" alt="<?=$petImage['alternative']?>">
            <?php } ?>
        </div>
    </div>  
<?php } ?>