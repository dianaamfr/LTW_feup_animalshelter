<?php
/**
 * Draw the Add Pet Form
 * @param allSpecies The available species
 * @param colors The available colors
 * @param sizes The available sizes
 * @param genders The available genders
 */
function drawAddPet($allSpecies, $colors, $sizes, $genders){ ?>

    <section id="add_pet">
        <h2>Found a pet </h2>

        <?php // If session is inactive
        if (!isset($_SESSION['username']) || $_SESSION['username'] == '') {
            drawInactiveSession('add a pet');
        }  // If the session is active
        else{ ?>
            <form action="../actions/action_add_pet.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                <fieldset id="load_data">
                    <label>Name
                        <input type="text" name="name" required="required" placeholder="Name">
                    </label>

                    <label>Location
                        <input type="text" name="location" required="required" placeholder="Location">
                    </label>

                    <label>Species
                        <select name="speciesId" required="required">
                            <option disabled="disabled" value="">None</option>
                            <?php foreach ($allSpecies as $species) { ?>
                                <option value="<?=$species['speciesId'] ?>"><?=$species['name'] ?></option>
                            <?php } ?>
                        </select>
                    </label>

                    <label>Breed
                        <input type="text" name="breed" placeholder="Breed">
                    </label>

                    <label>Color
                        <select name="colorId" required="required">
                            <option disabled="disabled" value="">None</option>
                            <?php foreach ($colors as $color) { ?>
                                <option value="<?=$color['colorId'] ?>"><?=$color['name'] ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    
                    <label>Age
                        <input type="number" min="1" name="age" required="required">
                    </label>

                    <fieldset id="gender">
                        <legend>Gender</legend>
                        <?php foreach ($genders as $gender) { ?>
                            <label>
                                <input type="radio" name="genderId" value="<?=$gender['genderId'] ?>" required="required">
                                <?=$gender['name'] ?>
                            </label>
                        <?php } ?>
                    </fieldset>

                    <label>Size
                        <select name="sizeId" required="required">
                            <option disabled="disabled" value="">None</option>
                            <?php foreach ($sizes as $size) { ?>
                                <option value="<?=$size['sizeId'] ?>"><?=$size['name'] ?></option>
                            <?php } ?>
                        </select>
                    </label>

                    <label>About
                            <textarea name="description" rows="4" cols="50"></textarea>
                    </label>
                </fieldset>   
                <fieldset id="load_pictures">
                    <legend>Pictures</legend>
                    
                    <?php
                    for($i = 0; $i < 4; $i++){?>
                        <fieldset>
                            <label>Title
                                <input type="text"  <?php if($i==0){ ?> placeholder="Main image title" required="required" <?php } ?> name="imagesAlts[]">
                            </label>
                            <input type="file" <?php if($i==0){ ?> required="required" <?php } ?> name="petImages[]" accept="image/png, image/jpeg, image/jpg">
                        </fieldset>
                    <?php } ?>
                </fieldset>
                <input type="submit" name="submit_pet" value="Add Pet">   
            </form>
        <?php } 
        
        drawMessages(); ?>
    </section>
<?php } ?>
