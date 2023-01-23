<?php

class Field_calculate {
    const PATTERN = '/(?:-?\d+(?:\.?\d+)?[+\-*\/])+-?\d+(?:\.?\d+)?/';

    const PARENTHESIS_DEPTH = 10;

    public function calculate($input){
        if(strpos($input, '+') != null || strpos($input, '-') != null || strpos($input, '/') != null || strpos($input, '*') != null){
            //  Remove white spaces and invalid math chars
            $input = str_replace(',', '.', $input);
            $input = preg_replace('[^0-9\.\+-\*/\(\)]', '', $input);

            //  Calculate each of the parenthesis from the top
            $i = 0;
            while(strpos($input, '(') || strpos($input, ')')){
                $input = preg_replace_callback('/\(([^()]+)\)/', 'self::callback', $input);

                $i++;
                if($i > self::PARENTHESIS_DEPTH){
                    break;
                }
            }

            //  Calculate the result
            if(preg_match(self::PATTERN, $input, $match)){
                return $this->compute($match[0]);
            }
            // To handle the special case of expressions surrounded by global parenthesis like "(1+1)"
            if(is_numeric($input)){
                return $input;
            }

            return 0;
        }

        return $input;
    }

    private function compute($input){
        $compute = create_function('', 'return '.$input.';');

        return 0 + $compute();
    }

    private function callback($input){
        if(is_numeric($input[1])){
            return $input[1];
        }
        elseif(preg_match(self::PATTERN, $input[1], $match)){
            return $this->compute($match[0]);
        }

        return 0;
    }
}

function getStatistics() {
    return array(
        "minecraft:animals_bred" =>                     array("name" => "Animals Bred", "factor" => "", "symbol" => ""),
        "minecraft:armor_cleaned" =>                    array("name" => "Armor Pieces Cleaned", "factor" => "", "symbol" => ""),
        "minecraft:banner_cleaned" =>                   array("name" => "Banners Cleaned", "factor" => "", "symbol" => ""),
        "minecraft:open_barrel" =>                      array("name" => "Barrels Opened", "factor" => "", "symbol" => ""),
        "minecraft:bell_ring" =>                        array("name" => "Bells Rung", "factor" => "", "symbol" => ""),
        "minecraft:cake_slices_eaten" =>                array("name" => "Cake Slices Eaten", "factor" => "", "symbol" => ""),
        "minecraft:cauldron_filled" =>                  array("name" => "Cauldrons Filled", "factor" => "", "symbol" => ""),
        "minecraft:chest_opened" =>                     array("name" => "Chests Opened", "factor" => "", "symbol" => ""),
        "minecraft:damage_absorbed" =>                  array("name" => "Damage Absorbed", "factor" => "", "symbol" => ""),
        "minecraft:damage_blocked_by_shield" =>         array("name" => "Damage Blocked By Shield", "factor" => "", "symbol" => ""),
        "minecraft:damage_dealt" =>                     array("name" => "Damage Dealt", "factor" => "", "symbol" => ""),
        "minecraft:damage_dealt_absorbed" =>            array("name" => "Damage Dealt (Absorbed)", "factor" => "", "symbol" => ""),
        "minecraft:damage_dealt_resisted" =>            array("name" => "Damage Dealt (Resisted)", "factor" => "", "symbol" => ""),
        "minecraft:damage_resisted" =>                  array("name" => "Damage Resisted", "factor" => "", "symbol" => ""),
        "minecraft:damage_taken" =>                     array("name" => "Damage Taken", "factor" => "", "symbol" => ""),
        "minecraft:dispenser_inspected" =>              array("name" => "Dispensers Searched", "factor" => "", "symbol" => ""),
        "minecraft:climb_one_cm" =>                     array("name" => "Distance Climbed", "factor" => "/100", "symbol" => "m"),
        "minecraft:crouch_one_cm" =>                    array("name" => "Distance Crouched", "factor" => "/100", "symbol" => "m"),
        "minecraft:fall_one_cm" =>                      array("name" => "Distance Fallen", "factor" => "/100", "symbol" => "m"),
        "minecraft:fly_one_cm" =>                       array("name" => "Distance Flown", "factor" => "/100000", "symbol" => "km"),
        "minecraft:sprint_one_cm" =>                    array("name" => "Distance Sprinted", "factor" => "/100000", "symbol" => "km"),
        "minecraft:swim_one_cm" =>                      array("name" => "Distance Swum", "factor" => "/100000", "symbol" => "km"),
        "minecraft:walk_one_cm" =>                      array("name" => "Distance Walked", "factor" => "/100000", "symbol" => "km"),
        "minecraft:walk_on_water_one_cm" =>             array("name" => "Distance Walked on Water", "factor" => "/100000", "symbol" => "km"),
        "minecraft:walk_under_water_one_cm" =>          array("name" => "Distance Walked under Water", "factor" => "/100000", "symbol" => "km"),
        "minecraft:boat_one_cm" =>                      array("name" => "Distance by Boat", "factor" => "/100000", "symbol" => "km"),
        "minecraft:aviate_one_cm" =>                    array("name" => "Distance by Elytra", "factor" => "/100000", "symbol" => "km"),
        "minecraft:horse_one_cm" =>                     array("name" => "Distance by Horse", "factor" => "/100000", "symbol" => "km"),
        "minecraft:minecart_one_cm" =>                  array("name" => "Distance by Minecart", "factor" => "/100000", "symbol" => "km"),
        "minecraft:pig_one_cm" =>                       array("name" => "Distance by Pig", "factor" => "/100", "symbol" => "m"),
        "minecraft:strider_one_cm" =>                   array("name" => "Distance by Strider", "factor" => "/100", "symbol" => "m"),
        "minecraft:inspect_dropper" =>                  array("name" => "Droppers Searched", "factor" => "", "symbol" => ""),
        "minecraft:enderchest_opened" =>                array("name" => "Ender Chests Opened", "factor" => "", "symbol" => ""),
        "minecraft:fish_caught" =>                      array("name" => "Fish Caught", "factor" => "", "symbol" => ""),
        "minecraft:leave_game" =>                       array("name" => "Games Quit", "factor" => "", "symbol" => ""),
        "minecraft:hopper_inspected" =>                 array("name" => "Hoppers Searched", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_anvil" =>              array("name" => "Interactions with Anvil", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_beacon" =>             array("name" => "Interactions with Beacon", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_blast_furnace" =>      array("name" => "Interactions with Blast Furnace", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_brewingstand" =>       array("name" => "Interactions with Brewing Stand", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_campfire" =>           array("name" => "Interactions with Campfire", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_cartography_table" =>  array("name" => "Interactions with Cartography Table", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_crafting_table" =>     array("name" => "Interactions with Crafting Table", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_furnace" =>            array("name" => "Interactions with Furnace", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_grindstone" =>         array("name" => "Interactions with Grindstone", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_lectern" =>            array("name" => "Interactions with Lectern", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_loom" =>               array("name" => "Interactions with Loom", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_smithing_table" =>     array("name" => "Interactions with Smithing Table", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_smoker" =>             array("name" => "Interactions with Smoker", "factor" => "", "symbol" => ""),
        "minecraft:interact_with_stonecutter" =>        array("name" => "Interactions with Stonecutter", "factor" => "", "symbol" => ""),
        "minecraft:drop_count" =>                       array("name" => "Items Dropped", "factor" => "", "symbol" => ""),
        "minecraft:item_enchanted" =>                   array("name" => "Items Enchanted", "factor" => "", "symbol" => ""),
        "minecraft:jump" =>                             array("name" => "Jumps", "factor" => "", "symbol" => ""),
        "minecraft:mob_kills" =>                        array("name" => "Mob Kills", "factor" => "", "symbol" => ""),
        "minecraft:record_played" =>                    array("name" => "Music Discs Played", "factor" => "", "symbol" => ""),
        "minecraft:noteblock_played" =>                 array("name" => "Note Blocks Played", "factor" => "", "symbol" => ""),
        "minecraft:noteblock_tuned" =>                  array("name" => "Note Blocks Tuned", "factor" => "", "symbol" => ""),
        "minecraft:deaths" =>                           array("name" => "Number of Deaths", "factor" => "", "symbol" => ""),
        "minecraft:flower_potted" =>                    array("name" => "Plants Potted", "factor" => "", "symbol" => ""),
        "minecraft:player_kills" =>                     array("name" => "Player Kills", "factor" => "", "symbol" => ""),
        "minecraft:raid_trigger" =>                     array("name" => "Raids Triggered", "factor" => "", "symbol" => ""),
        "minecraft:raid_win" =>                         array("name" => "Raids Won", "factor" => "", "symbol" => ""),
        "minecraft:clean_shulker_box" =>                array("name" => "Shulker Boxes Cleaned", "factor" => "", "symbol" => ""),
        "minecraft:shulker_box_opened" =>               array("name" => "Shulker Boxes Opened", "factor" => "", "symbol" => ""),
        "minecraft:sneak_time" =>                       array("name" => "Sneak Time", "factor" => "/20/60", "symbol" => "m"),
        "minecraft:talked_to_villager" =>               array("name" => "Talked to Villagers", "factor" => "", "symbol" => ""),
        "minecraft:target_hit" =>                       array("name" => "Targets Hit", "factor" => "", "symbol" => ""),
        "minecraft:play_one_minute" =>                  array("name" => "Time Played", "factor" => "/20/3600", "symbol" => "h"),
        "minecraft:time_since_death" =>                 array("name" => "Time Since Last Death", "factor" => "/20/3600", "symbol" => "h"),
        "minecraft:time_since_rest" =>                  array("name" => "Time Since Last Rest", "factor" => "/20/3600", "symbol" => "h"),
        "minecraft:total_world_time" =>                 array("name" => "Time with World Open", "factor" => "/20/3600", "symbol" => "h"),
        "minecraft:sleep_in_bed" =>                     array("name" => "Times Slept in a Bed", "factor" => "", "symbol" => ""),
        "minecraft:traded_with_villager" =>             array("name" => "Traded with Villagers", "factor" => "", "symbol" => ""),
        "minecraft:trapped_chest_triggered" =>          array("name" => "Trapped Chests Triggered", "factor" => "", "symbol" => ""),
        "minecraft:cauldron_used" =>                    array("name" => "Water Taken from Cauldron", "factor" => "", "symbol" => "")
    );
}