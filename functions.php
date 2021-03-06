add_filter( 'gform_pre_render_1', 'populate_posts_custom' );
add_filter( 'gform_pre_validation_1', 'populate_posts_custom' );
add_filter( 'gform_pre_submission_filter_1', 'populate_posts_custom' );
add_filter( 'gform_admin_pre_render_1', 'populate_posts_custom' );
function populate_posts_custom( $form ) {
    foreach ( $form['fields'] as $field ) {
        if ( strpos( $field->cssClass, 'populate_monthly_contribution_lists' ) === false ) {
            continue;
        }
        $monthly_contribution_choices = array();
        if (isset($_GET['customer'])) {
            $customer_id = array($_GET["customer"]);
        } else {
            $customer_id = array(47);
        }
        $monthly_contribution_args = array(
            'posts_per_page'    => -1,
            'post_type'         => 'customer',
            'post__in'          => $customer_id,
        );
        $monthly_contributions = new WP_Query($monthly_contribution_args);
        if ($monthly_contributions->have_posts()) {
            while($monthly_contributions->have_posts()){
                $monthly_contributions->the_post();
                if( have_rows('monthly_contribution_lists') ){
                    while( have_rows('monthly_contribution_lists') ) { the_row();
                        $contribution_value = get_sub_field('contribution_title');
                        $monthly_contribution_choices[] = array( 'text' => $contribution_value, 'value' => $contribution_value );
                    };
                };
            };
        };
        wp_reset_query();
        $field->placeholder = 'Monatlicher Gesamtbeitrag';
        $field->choices = $monthly_contribution_choices;
    }
    return $form;
}
