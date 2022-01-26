
if (is_admin()) {
	// Add a custom field to product bulk edit special page
	add_action( 'woocommerce_product_bulk_edit_start', 'custom_field_product_bulk_edit', 10, 0 );
	function custom_field_product_bulk_edit() {
		?>

			<?php $brands = get_terms('brands', array('parent' => 0)); ?>
			
			<fieldset class="inline-edit-col-center inline-edit-brands" style="width:100%">
				<div class="inline-edit-col">

					<span class="title inline-edit-brands-label">Product Brands <span color="red">(under development)</span></span>
					<input type="hidden" name="brand_input[brands][]" value="0">
					<ul class="cat-checklist brands-checklist" style="width: 100%">
						
						<?php foreach($brands as $brand) : ?>
						<li id="brands-<?php echo $brand->term_id; ?>" class="popular-category"><label class="selectit"><input value="<?php echo $brand->term_id; ?>" type="checkbox" name="brand_input[brands][]" id="in-brands-<?php echo $brand->term_id; ?>"> <?php echo $brand->name; ?> </label>
							
							<?php 
								$level2 = get_terms('brands', array('parent' => $brand->term_id));
								if (!empty($level2)) : 
							?>
							<ul class="children">
								<?php foreach($level2 as $child1) : ?>
									<li id="brands-<?php echo $child1->term_id; ?>" class="popular-category"><label class="selectit"><input value="<?php echo $child1->term_id; ?>" type="checkbox" name="brand_input[brands][]" id="in-brands-<?php echo $child1->term_id; ?>"> <?php echo $child1->name; ?> </label>

										<?php 
											$level3 = get_terms('brands', array('parent' => $child1->term_id));
											if (!empty($level3)) : 
										?>
											<ul class="children">
											<?php foreach($level3 as $child2) : ?>
												<li id="brands-<?php echo $child2->term_id; ?>" class="popular-category"><label class="selectit"><input value="<?php echo $child2->term_id; ?>" type="checkbox" name="brand_input[brands][]" id="in-brands-<?php echo $child2->term_id; ?>"> <?php echo $child2->name; ?> </label>
											<?php endforeach; ?>
											</ul>
											
										<?php endif; ?>

									</li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</fieldset>
		<?php
	}

	// Save the custom fields data when submitted for product bulk edit
	add_action('woocommerce_product_bulk_edit_save', 'save_custom_field_product_bulk_edit', 10, 1);
	function save_custom_field_product_bulk_edit( $product ){
		if ( $product->is_type('simple') || $product->is_type('external') ){
			$product_id = method_exists( $product, 'get_id' ) ? $product->get_id() : $product->id;

			if ( isset( $_REQUEST['brand_input'] ) ) {
				
				$currentterms = wp_get_post_terms( $product_id, 'brands' );
				$updatedBrands = $_REQUEST['brand_input']['brands'];
				foreach($currentterms as $term) {
					$updatedBrands[] = $term->term_id;
				}

				wp_set_post_terms( $product_id, array_unique($updatedBrands), 'brands' );
			}	
		}
	}
}
