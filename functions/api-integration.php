<?php
/** Henter data lokalt via WordPress REST API */

function hent_data_fra_api($category_id) {

    $url = 'https://www.lundhjemmesider-udvikling.dk/jumbotransport_dk/wp-json/wp/v2/posts?order=desc&orderby=date&_embed&categories=' . $category_id;

    $args = array(
        'timeout' => 5,
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode('USERNAME:PASSWORD'),
        ),
    );

    $response = wp_remote_get($url, $args);

    if (is_wp_error($response)) {
        return 'Fejl ved hentning af data: ' . $response->get_error_message();
    }

    $data = wp_remote_retrieve_body($response);
    
    if (empty($data)) {
        return 'Ingen data modtaget.';
    }

    return json_decode($data);
}

function vis_nyheder() {
    $data = hent_data_fra_api(13);

    if (is_string($data)) {
        return $data;
    }

    $output = '<ul class="nyheder-list">'; 
    
    foreach ($data as $item) {
        $post_id = $item->id; // Postens ID fra API'en
        $post_url = get_permalink($post_id); // Hent permalink baseret på postens ID

        $output .= '<li class="nyheder-item"';

        // Tilføj baggrundsbilledet som en inline-stil, hvis der er et featured billede
        if (!empty($item->featured_media)) {
            $image_sizes = isset($item->_embedded->{'wp:featuredmedia'}) ? $item->_embedded->{'wp:featuredmedia'}[0]->media_details->sizes : null;
            if ($image_sizes && isset($image_sizes->full->source_url)) {
                $image_url = esc_url($image_sizes->full->source_url);
                $output .= ' style="background-image: url(' . $image_url . ');"';
            }
        }

        $output .= '>';

        // Gør hele elementet klikbart med et link
        $output .= '<a href="' . esc_url($post_url) . '" class="nyheder-link">';

        $output .= '<div class="nyheder-title-box"><h3 class="nyheder-title-item">' . esc_html($item->title->rendered) . '</h3></div>';

        $output .= '<div class="nyheder-media-content-container">';
        
        // Dato
        setlocale(LC_TIME, 'da_DK.UTF-8', 'da_DK', 'danish');
        $date = $item->date;
        $formatted_date = strftime('%e. %B %Y', strtotime($date));
        $output .= '<p class="nyheder-date">' . esc_html($formatted_date) . '</p>';
        
        // Uddrag
        $output .= '<p class="nyheder-excerpt">' . wp_kses_post(wp_trim_words($item->excerpt->rendered, 10)) . '</p>';

        $output .= '</div></a></li>';
    }
    
    $output .= '</ul>';

    return $output;
}

add_shortcode('vis_nyheder', 'vis_nyheder');



function vis_olietillaeg() {
    $data = hent_data_fra_api(20);

    if (is_string($data)) {
        return $data; // Returnerer fejlmeddelelse
    }

    $output = '<div>';  
    foreach ($data as $item) {
        
        $output .= '<h2 class="oil-title">' . esc_html($item->title->rendered) . '</h2>';

        $content = wp_kses_post($item->content->rendered); 
        $content = preg_replace('/<p>/i', '<p class="oil-content">', $content); 

        $output .= $content;
        
        $date = $item->date;
		$formatted_date = date('j. F, Y', strtotime($date));
		if (isset($item->date)) {
    	$output .= '<p class="publish-date">Opdateret: ' . esc_html($formatted_date) . '</p>';
		}
    }
    $output .= '</div>';

    return $output;
}

add_shortcode('vis_olietillaeg', 'vis_olietillaeg');


function wpnw_modify_news_post_slug( $slug ){
$slug = 'news';
return $slug;
}
add_filter( 'wpnw_news_post_slug', 'wpnw_modify_news_post_slug' );
?>
