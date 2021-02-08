<?php
    function drawEditPet($allSpecies, $colors, $sizes, $genders, $pet, $actualNumberOfImages){ ?>

    <section id="add_pet">
        <h2>Edit pet </h2>

        <?php // Show message if session is inactive
        if (!isset($_SESSION['username']) || $_SESSION['username'] == ''){
            drawInactiveSession('edit a pet');
        }  // Show edit form if the user is authenticated
        else{
        ?>
            <form action="../actions/action_edit_pet.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                <input type="hidden" name="petId" value="<?=$pet['petId']?>">
                <fieldset id="load_data">
                    <label>Name
                        <input type="text" name="name" required="required" value="<?=$pet['name']?>">  
                    </label>

                    <label>Location
                        <input type="text" name="location" required="required" value="<?=$pet['location']?>">
                    </label>

                    <label>Species
                    <select name="speciesId" required="required">
                        <option disabled="disabled" value="">None</option>
                        <?php foreach ($allSpecies as $species) { 
                            if($pet['species'] != $species['name'] ){ ?>
                                <option value="<?=$species['speciesId'] ?>"><?=$species['name'] ?></option>
                            <?php }
                            else { ?>
                                <option value="<?=$species['speciesId'] ?>" selected="selected"><?=$pet['species']?></option>
                            <?php }
                        } ?>
                    </select>
                    </label>

                    <label>Breed
                        <input type="text" name="breed" value="<?=$pet['breed']?>"">
                    </label>

                    <label>Color
                        <select name="colorId" required="required">
                            <option disabled="disabled" value="">None</option>
                            <?php foreach ($colors as $color) { ?>
                                <option value="<?=$color['colorId']?>"><?=$color['name'] ?></option>
                            <?php } ?>
                            
                        </select>
                    </label>                        
                    
                    <label>Age
                        <input type="number" min="1" name="age" value="<?=$pet['age']?>" required="required">
                    </label>

                    <fieldset id="gender">
                        <legend>Gender</legend>
                        <?php foreach ($genders as $gender) { ?>
                            <label>
                                <input type="radio" name="genderId" value="<?=$gender['genderId'] ?>" required="required" <?php if($gender['genderId'] == $pet['genderId']){ ?> checked="checked" <?php } ?>>
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
                            <textarea name="description" rows="4" cols="50"><?=$pet['description']?></textarea>
                    </label>
                </fieldset>   
                
                <fieldset id="load_pictures">
                    <legend>Add new Pictures</legend>
                    
                    <?php
                    for($i = 0; $i < (4 - $actualNumberOfImages['count']); $i++){ ?>
                        <fieldset>
                            <label>Title
                                <input type="text" name="imagesAlts[]">
                            </label>
                            <input type="file" name="petImages[]" accept="image/png, image/jpeg, image/jpg">
                        </fieldset>
                    <?php } ?>
                </fieldset>
                <input type="submit" name="submit_pet" value="Edit Pet">   
                <input type="submit" name="delete_pet_btn" value="delete pet" formaction="../actions/action_delete_pet.php">
            </form>
    
    <?php } 
    
    drawMessages(); ?>
    </section>
<?php } ?>
