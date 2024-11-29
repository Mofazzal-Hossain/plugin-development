<?php

function exsintem_register_recipe_cpt()
{

    /**
     * Post Type: Recipes.
     */

    $labels = [
        "name" => esc_html__("Recipes", "extend-single-template"),
        "singular_name" => esc_html__("Recipe", "extend-single-template"),
    ];

    $args = [
        "label" => esc_html__("Recipes", "extend-single-template"),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "rest_controller_class" => "WP_REST_Posts_Controller",
        "rest_namespace" => "wp/v2",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "delete_with_user" => false,
        "exclude_from_search" => false,
        "capability_type" => "post",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "can_export" => false,
        "rewrite" => ["slug" => "recipe", "with_front" => true],
        "query_var" => true,
        "menu_icon" => "dashicons-store",
        "supports" => ["title", "editor", "thumbnail"],
        "show_in_graphql" => false,
    ];

    register_post_type("recipe", $args);
}

add_action('init', 'exsintem_register_recipe_cpt');
