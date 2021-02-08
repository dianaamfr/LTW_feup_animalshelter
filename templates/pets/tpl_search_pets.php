<?php
/**
 * Draw pet search filters
 * @param allSpecies The available species
 * @param colors The available colors
 * @param sizes The available sizes
 * @param genders The available genders
 */
function drawSearchFilters($allSpecies, $colors, $sizes, $genders){ ?>
    <form id="caracteristicas" action="#" method="get">
    <h2>Adopt a pet</h2>
    <fieldset id="pet_type">
        <legend>Species</legend>
        
        <?php
            foreach($allSpecies as $species){?>
                <label>
                    <input type="checkbox" name="species[]" value="<?=$species['speciesId']?>" />
                    <span class="pet_icon">
                        <img src="../images/categories/<?=strtolower($species['name']);?>.svg" alt=""/>
                    </span>
                    <?=$species['name'] ?>
                </label>
        <?php } ?>
        
    </fieldset>

    <fieldset id="pet_othersettings">
        <label>Location
            <input type="text" id="location" name="location">
        </label>
        
        <label>Color
            <select id="color">
                <option value="-1">Any</option>
                <?php foreach ($colors as $color) { ?>
                    <option value="<?=$color['colorId'] ?>">
                    <?=$color['name'] ?></option>
                <?php } ?>
            </select>
        </label>

        <label>Breed
            <input type="text" id="breed" name="breed">
        </label>
    </fieldset>

    <fieldset id="pet_gender">
        <legend>Gender</legend>
        <?php foreach ($genders as $gender) { ?>
            <label>
                <input type="radio" name="gender" value="<?=$gender['genderId']?>">
                <?=$gender['name'] ?>
            </label>
        <?php } ?>
        <input type="radio" name="gender" value="-1" checked>Any

    </fieldset>

    <fieldset id="pet_size">
        <legend>Size</legend>
        
        <?php foreach ($sizes as $size) { ?>
            <label>
                <input type="checkbox" name="sizes[]" value="<?=$size['sizeId']?>">
                <?=$size['name'] ?>
            </label>
        <?php } ?>

    </fieldset>

    <fieldset id="pet_age">
        <legend>Age</legend>
        
        <input type="radio" id="-2" name="age" value="0-2">
        <label for="-2">&lt; 2</label>
        
        <input type="radio" id="2-6" name="age" value="2-6">
        <label for="2-6">2-6</label>

        <input type="radio" id="7-10" name="age" value="7-10">
        <label for="7-10">7-10</label>
        
        <input type="radio" id="+10" name="age" value="10- ">
        <label for="+10">&gt; 10</label>
        
        <input type="radio" id="any" name="age" value="0- " checked>
        <label for="any">Any</label>
        
    </fieldset>
    <input class="beige_btn" type="submit" value="Search">
</form>
<?php } ?>
