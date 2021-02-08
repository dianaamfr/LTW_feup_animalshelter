<?php 
/**
 * Draw the last message of each conversation
 * @param lastMessages The last Message of every Conversation
 */
function drawConversationsLastMessages($lastMessages) { ?>
    <section id="messages_preview">
        <h2>My Conversations</h2>

        <?php 
        // If the user is authenticated but he has no conversations yet
        if (empty($lastMessages)){?>
            <span class="error">You don't have any conversations yet!</span>
        <?php } 
        // If the user is authenticated and has adoption proposals
        else {?>
            <div id="messages_preview_content">
                <?php foreach($lastMessages as $lastMessage){ ?>
                    <?php drawConversationPreview($lastMessage); ?>
                <?php } ?>
            </div>
        <?php } ?>
        <?php  drawMessages(); ?> 
    </section>
<?php } ?>

<?php
/**
 * Draw a conversation
 * @param conversationId The id of the conversation
 * @param pet The pet which the conversation refers to
 * @param petImage One image of the pet
 */
function drawConversation($conversationId, $pet, $petImage){ ?>
    <div id="conversation">
        <header>
            <img src="../images/pets/thumbs/pet<?=$pet['petId']?>img<?=$petImage['imageId']?>.jpg" alt="<?=$petImage['alternative']?>">
            <h3><a href="../pages/pet_item.php?id=<?=$pet['petId']?>"><?=$pet['name']?></a></h3>
        </header>
        <div id="conversation_messages">
            
        </div>
        <form class="new_message">
            <input type="hidden" name="conversationId" value="<?=$conversationId?>">
            <input type="text" name="message" placeholder="message">
            <input type="submit" value="Send" class="green_btn">
        </form>

        <div class="messages">
        </div>

    </div>
<?php } ?>

<?php
/**
 * Draw the Preview of a Conversation
 * @param The first Message of a Conversation
 */
function drawConversationPreview($message){ ?>
    <div class="message_preview">
        <header>
            <section>
                <h4>Date: </h4>
                <span><?=$message['date']?></span>
            </section>
            <section>
                <h4>Pet: </h4>
                <span><?=$message['name']?></span>
            </section>
        </header>
        <div class="message_preview_content">
            <img src="../images/pets/thumbs/pet<?=$message['petId']?>img<?=$message['imageId']?>.jpg" alt="<?=$message['alternative']?>">
            <section class="message_sender">
                <h3>From: </h3>
                <span><?= (strcmp($_SESSION['username'], $message['sender']) === 0) ? "You" :  $message['sender'] ?></span>
            </section>
            <section class="message_receiver">
                <h3>To: </h3>
                <span><?= (strcmp($_SESSION['username'], $message['receiver'])) === 0 ? "You" :  $message['receiver'] ?></span>
            </section>
            <section class="message_text">
                <h3>Last Message about <a href="../pages/pet_item.php?id=<?=$message['petId']?>"><?=$message['name']?></a>: </h3>
                <span><?=htmlspecialchars($message['messageText'])?></span>
            </section>
        </div>
        <footer>
            <a class="green_btn" href="../pages/conversation.php?id=<?=$message['conversationId']?>">Full Conversation</a>
        </footer>
</div>
<?php } ?>
