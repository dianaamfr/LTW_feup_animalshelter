<?php 
/**
 * Draw pending proposals
 * @param proposals The proposals to draw
 */
function drawReceivedProposals($proposals){?>
    <section id="my_proposals_page">
        <h2>Pending Proposals</h2>
    
        <?php 
        // If the session is inactive
        if (!isset($_SESSION['username']) || ($_SESSION['username'] == '')){
            drawInactiveSession('see received proposals');
        }
        else{
            // If the session is active but the user does not have any proposals
        if (empty($proposals)){?>
            <span class="error">You don't have any pending adoption proposal!</span>
        
        <?php } 
        // If the session is active and the user has received proposals
        else {?>
            <div id="received_proposals">

            <?php 
            if(isset($proposals)){
                foreach($proposals as $proposal) {?>
                    <article class="proposal">
                        <header>
                            <section>
                                <h4>Date: </h4>
                                <span><?=$proposal['date']?></span>
                            </section>
                            <section>
                                <h4>User: </h4>
                                <span><?=$proposal['username']?></span>
                            </section>
                        </header>
                        <div class="proposal_content">
                            <img src="../images/pets/thumbs/pet<?=$proposal['petId']?>img<?=$proposal['imageId']?>.jpg" alt=<?=$proposal['alternative']?>>
                            <section class="pet_name">
                                <h3>Pet: </h3>
                                <span><?=$proposal['name']?></span>
                            </section>
                            <section class="proposal_text">
                                <h3>Proposal: </h3>
                                <span><?=htmlspecialchars($proposal['proposalText'])?></span>
                            </section>
                        </div>
                        <form class="handle_proposal" action="../actions/action_accept_proposal.php" method="post">
                            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                            <input type="hidden" name="proposalId" value="<?=$proposal['proposalId']?>">
                            <input class="accept" type="submit" value="Accept">
                            <input class="reject" type="submit" value="Decline" formaction="../actions/action_reject_proposal.php">
                        </form>
                        <form class="contact_interested" action="../actions/action_send_message.php" method="post">
                            <h3>Send a message</h3>
                            <label>
                                <input type="hidden" name="petId" value="<?=$proposal['petId']?>">
                                <input type="hidden" name="receiver" value="<?=$proposal['username']?>">
                                <textarea name="messageText" rows="4" cols="40" required=""></textarea>
                            </label>
                            <input class="contact" type="submit" value="Send Message">
                        </form>
                    </article>
                <?php } 
            }   ?>
            
            </div>
        <?php } ?>
        
        <!-- Imprimir mensagens de erro do login -->
        <?php  drawMessages(); ?>
       <?php } ?>
    
    </section>
<?php } ?>

<?php 
/**
 * Draw all the proposals made by the user
 * @param proposals The proposals to draw
 */
function drawMyProposals($proposals) {?>
    <section id="my_proposals_page">
        <h2>My Proposals</h2>
        
        <?php 
        // If the user is not authenticated
        if (!isset($_SESSION['username']) || $_SESSION['username'] == ''){
           drawInactiveSession('see your proposals');
        } 
        // If the user is authenticated but there are not any adoption proposals
        else if (empty($proposals)){?>
            <span class="error">You haven't made any adoption proposal yet!</span>
            <a class="green_btn" href="../pages/adopt_pets.php">Adopt a pet</a>

        <?php } 
        // If the user is authenticated and he has made adoption proposals
        else {?>
            <div id="sent_proposals">
                <?php foreach($proposals as $proposal) {?>
                    <article class="proposal">
                        <header>
                            <section>
                                <h4>Date: </h4>
                                <span><?=$proposal['date']?></span>
                            </section>
                            <section>
                                <h4>Owner: </h4>
                                <span><?=$proposal['owner']?></span>
                            </section>
                        </header>
                        <div class="proposal_content">
                            <img src="../images/pets/thumbs/pet<?=$proposal['petId']?>img<?=$proposal['imageId']?>.jpg" alt="<?=$proposal['alternative']?>">
                            <section class="pet_name">
                                <h3>Pet: </h3>
                                <span><?=$proposal['name']?></span>
                            </section>
                            <section class="proposal_text">
                                <h3>Proposal: </h3>
                                <span><?=$proposal['proposalText']?></span>
                            </section>
                            <section class="proposal_state">
                                <h3>State: </h3>
                                <span class="<?=$proposal['proposalState']?>"><?=$proposal['proposalState']?></span>
                            </section>
                        </div>
                        <footer>
                            <a class="green_btn" value="More" href="../pages/pet_item.php?id=<?=$proposal['petId']?>">More</a>
                        </footer>

                    </article>
                <?php } ?>
                </div>
            <?php } ?>

        <?php  drawMessages(); ?> 

    </section>
<?php } ?>