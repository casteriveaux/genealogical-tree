<?php

/**
* Provide a admin area view for the plugin
*
* This file is used to markup the admin-facing aspects of the plugin.
*
* @link       https://wordpress.org/plugins/genealogical-tree
* @since      1.0.0
*
* @package    Genealogical_Tree
* @subpackage Genealogical_Tree/admin/partials
*/
?>
<div class="gta-container">
	<?php wp_nonce_field( 'update_member_info_nonce', '_nonce_update_member_info_nonce' ); ?>
	<div class="gta-row">
		<div class="gta-col-3">
			<table class="gta-table">
				<tr>
					<td colspan="2">
						<h4><?php _e('Name', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td>
						<label for="full-name"><?php _e('Full Name', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<input id="full-name" type="text" name="gt[full_name]" value="<?php echo $full_name; ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="given-name"><?php _e('Given Name', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<input id="given-name" type="text" name="gt[given_name]" value="<?php echo $given_name; ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="sur-name"><?php _e('Surname', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<input id="surname" type="text" name="gt[surname]" value="<?php echo $surname; ?>">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h4><?php _e('Gender', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td>
						<label for="birth-sex"><?php _e('Gender', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<select id="birth-sex" name="gt[sex]">
							<option value=""><?php _e('Select Gender', 'genealogical-tree'); ?></option>
							<option value="M" <?php echo ($sex==='M') ? 'selected' : '' ; ?>><?php _e('Male', 'genealogical-tree'); ?></option>
							<option value="F" <?php echo ($sex==='F') ? 'selected' : '' ; ?>><?php _e('Female', 'genealogical-tree'); ?></option>
						</select>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<h4><?php _e('Birth', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td colspan="2">				
						<?php 
						$bc = 0; 
						foreach ($event['birt'] as $key => $value) {
						?>
						<div class="repetead-field" style="margin-left: -3px; margin-right: -3px;">
							<?php if ($bc===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($bc > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td style="width: 150px;">
										<?php _e('REF #', 'genealogical-tree'); ?> <span data-ref-c="<?php echo $bc; ?>"><?php echo $bc+1; ?></span> 
									</td>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td>
													<label for="birt-date"><?php _e('Date', 'genealogical-tree'); ?></label>
												</td>
												<td>
													<input id="birt-date" type="text" name="gt[event][birt][<?php echo $key; ?>][date]"  value="<?php echo $value['date']; ?>">
												</td>
											</tr>
											<tr>
												<td>
													<label for="birt-place"><?php _e('Place', 'genealogical-tree'); ?></label>
												</td>
												<td>
													<input id="birt-place" type="text" name="gt[event][birt][<?php echo $key; ?>][place]" value="<?php echo isset($value['place']) ? $value['place'] : ''; ?>">
													<input id="birt-ref" type="hidden" name="gt[event][birt][<?php echo $key; ?>][ref]" >
													<input id="birt-ref" type="hidden" name="gt[event][birt][<?php echo $key; ?>][type]" value="birt">
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>

				<?php $bc++; } ?>
					</td>
				</tr>				
				<tr>
					<td colspan="2">
						<h4><?php _e('Death', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td colspan="2">				
					<?php $dc = 0; foreach ($event['deat'] as $key => $value) { ?>
						<div class="repetead-field" style="margin-left: -3px; margin-right: -3px;">
							<?php if ($dc===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($dc > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td style="width: 150px;">
										<?php _e('REF #', 'genealogical-tree'); ?><span data-ref-c="<?php echo $dc; ?>"> <?php echo $dc+1; ?>
									</td>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td>
													<label for="deat-date"><?php _e('Date', 'genealogical-tree'); ?></label>
												</td>
												<td>
													<input id="deat-date" type="text" name="gt[event][deat][<?php echo $key; ?>][date]" value="<?php echo $value['date']; ?>">
													<br><small><i><?php _e('Leave empty if still alive', 'genealogical-tree'); ?></i></small>
												</td>
											</tr>
											<tr>
												<td>
													<label for="deat-place"><?php _e('Place', 'genealogical-tree'); ?></label>
												</td>
												<td>
													<input id="deat-place" type="text" name="gt[event][deat][<?php echo $key; ?>][place]" value="<?php echo isset($value['place']) ? $value['place'] : ''; ?>">
													<input id="deat-ref" type="hidden" name="gt[event][deat][<?php echo $key; ?>][ref]">
													<input id="deat-ref" type="hidden" name="gt[event][deat][<?php echo $key; ?>][type]" value="birt">
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>

				<?php  $dc++; } ?>
					</td>
				</tr>				
			</table>
		</div>
		<div class="gta-col-3">
			<table class="gta-table" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td colspan="2">
						<h4><?php _e('Parents', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td>
						<label for="mother"><?php _e('Mother', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<select id="mother" name="gt[mother]">
							<option value=""><?php _e('Select Mother', 'genealogical-tree'); ?></option>
							<?php foreach ($mothers as $key => $value) { ?>
								<option <?php if($value==$mother) { echo 'selected'; } ?> value="<?php echo $value; ?>"><?php echo get_post_meta($value, 'full_name', true); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for="father"><?php _e('Father', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<select id="father" name="gt[father]">
							<option value=""><?php _e('Select Father', 'genealogical-tree'); ?></option>
							<?php foreach ($fathers as $key => $value) { ?>
								<option <?php if($value==$father) { echo 'selected'; } ?> value="<?php echo $value; ?>"><?php echo get_post_meta($value, 'full_name', true); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h4><?php _e('Spouses', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				
				<tr class="tr-wife" style="<?php if($sex==='F'){ echo 'display: none;'; } elseif($sex==='M') { echo 'display: table-row;'; } else {echo 'display: table-row;';} ?>">
					<td>
						<label for="wife"><?php _e('Wife', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<?php $y = 0; foreach ( $spouses as $key => $wife) { ?>
						<div class="repetead-field"> 
							<?php if ($y===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($y > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<select id="wife" name="gt[wife][<?php echo $y; ?>][id]">
								<option value=""><?php _e('Select Wife', 'genealogical-tree'); ?></option>
								<?php foreach ($mothers as $key => $value) {  ?>
									<option <?php if($wife['id']==$value) { echo 'selected'; } ?> value="<?php echo $value; ?>"><?php echo get_post_meta($value, 'full_name', true); ?></option>
								<?php } ?>
							</select>
							<!--<div style="margin-left: -3px; margin-right: -3px;">
								<table class="gta-table">
									<tr>
										<td><label for="father">From</label></td>
										<td><input type="text" name="gt[wife][<?php echo $y; ?>][from]"> </td>
										<td><label for="father">To</label></td>
										<td><input type="text" name="gt[wife][<?php echo $y; ?>][to]"> </td>
									</tr>
									<tr>
										<td colspan="2"><label for="father">Select a Reason</label></td>
										<td colspan="2">
											<select name="gt[wife][<?php echo $y; ?>][_to_reson]">
												<option value="">Select a Reason</option>
												<option value="divorce">Divorce</option>
												<option value="death">Death</option>
												<option value="other">Other</option>
											</select>
										</td>
									</tr>
								</table>
							</div>-->
						</div>
						<?php $y++;}  ?>
					</td>
				</tr>
				
				
				<tr class="tr-husb" style="<?php if($sex==='F'){ echo 'display: table-row;'; } elseif($sex==='M') { echo 'display: none;'; } else {echo 'display: table-row;';} ?>">
					<td>
						<label for="husb"><?php _e('Husband', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<?php $x = 0;  foreach ( $spouses as $key => $husb) { ?>
						<div class="repetead-field"> 
							<?php if ($x===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($x > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<select id="husb" name="gt[husb][<?php echo $x; ?>][id]">
								<option value=""><?php _e('Select Husband', 'genealogical-tree'); ?></option>
								<?php foreach ($fathers as $key => $value) { ?>
									<option <?php if($husb['id']==$value) { echo 'selected'; } ?> value="<?php echo $value; ?>"><?php echo get_post_meta($value, 'full_name', true); ?></option>
								<?php } ?>
							</select>
							<!--<div style="margin-left: -3px; margin-right: -3px;">
								<table class="gta-table">
									<tr>
										<td><label for="father">From</label></td>
										<td><input type="text" name="gt[husb][<?php echo $x; ?>][from]"> </td>
										<td><label for="father">To</label></td>
										<td><input type="text" name="gt[husb][<?php echo $x; ?>][to]"> </td>
									</tr>
									<tr>
										<td colspan="2"><label for="father">Select a Reason</label></td>
										<td colspan="2">
											<select name="gt[husb][<?php echo $x; ?>][_to_reson]">
												<option value="">Select a Reason</option>
												<option value="divorce">Divorce</option>
												<option value="death">Death</option>
												<option value="other">Other</option>
											</select>
										</td>
									</tr>
								</table>
							</div>-->
						</div>
						<?php $x++; } ?>
					</td>
				</tr>
				
				<tr>
					<td colspan="2">
						<h4><?php _e('Contact Information', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td>
						<label for="phone"><?php _e('Phone', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<?php foreach ($phone as $key => $phon) { ?>
							<div class="repetead-field"> 
							<?php if ($key===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($key > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
								<input type="text" id="phone" name="gt[phone][<?php echo $key; ?>]" value="<?php echo $phon; ?>">
							</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="email"><?php _e('Email', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<?php foreach ($email as $key => $emai) { ?>
						<div class="repetead-field"> 
							<?php if ($key===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($key > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<input type="text" id="email" name="gt[email][<?php echo $key; ?>]"  value="<?php echo $emai; ?>">
						</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>
						<label for="address"><?php _e('Address', 'genealogical-tree'); ?></label>
					</td>
					<td>
						<?php foreach ($address as $key => $addr) { ?>
						<div class="repetead-field"> 
							<?php if ($key===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($key > 0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>
							<input type="text" id="address" name="gt[address][<?php echo $key; ?>]"  value="<?php echo $addr; ?>">
						</div>
						<?php } ?>
					</td>
				</tr>
			</table>
		</div>

		<?php 
		unset($event['birt']);
		unset($event['deat']);

		if(empty($event)){
			$event[0][0] = array(
				'date' => '', 
				'place' => '',
				'type' => ''
			);
		}

		$aditionals_events = array(
			'buri' => array(
				'type' => 'buri',
				'title' => __('Burial', 'genealogical-tree'),
			),
			'adop' => array(
				'type' => 'adop',
				'title' => __('Adoption', 'genealogical-tree'),
			),
			'enga' => array(
				'type' => 'enga',
				'title' => __('Engagement', 'genealogical-tree'),
			),
			'marr' => array(
				'type' => 'marr',
				'title' => __('Marriage', 'genealogical-tree'),
			),
			'div' => array(
				'type' => 'div',
				'title' => __('Divorce', 'genealogical-tree'),
			),
			'address_(other)' => array(
				'type' => 'address_(other)',
				'title' => __('Address (Other)', 'genealogical-tree'),
			),
			'bapm' => array(
				'type' => 'bapm',
				'title' => __('Baptism', 'genealogical-tree'),
			),
			'chr' => array(
				'type' => 'chr',
				'title' => __('Christening', 'genealogical-tree'),
			),
			'arms' => array(
				'type' => 'arms',
				'title' => __('arms', 'genealogical-tree'),
			),
			'occupation_1' => array(
				'type' => 'occupation_1',
				'title' => __('Occupation', 'genealogical-tree'),
			),
		);
		?>

		<div class="gta-col-3">
			<table class="gta-table" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<h4><?php _e('Additional Events', 'genealogical-tree'); ?></h4>
					</td>
				</tr>
				<tr>
					<td>					
				<?php 
				
				$yc = 0; 


				
				foreach ($event as $key => $event_single_group) {  
					$xc = 0; 
					?>
					<?php				
					foreach ($event_single_group as $keyx => $value) {
						
					?>

						<div class="repetead-field"> 
							<?php if ($yc===0){ ?>
								<span class="clone"><?php _e('Add', 'genealogical-tree'); ?></span>
							<?php } if ($yc>0){ ?>
								<span class="delete"><?php _e('Delete', 'genealogical-tree'); ?></span>
							<?php }  ?>

							<table cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td style="width: 150px;">
										<label style="padding-right: 20px;" for="schooling">
											<select name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][type]">
												<option value="0">Select an Event</option>
												<?php 
												
												foreach ($aditionals_events as $keye => $valuee) { 
													if (!isset($aditionals_events[$key]['type'])) {
														if($event_single_group[$keyx]['type']){
															$aditionals_events[$event_single_group[$keyx]['type']] = array(
																'type' => $event_single_group[$keyx]['type'],
																'title' => ucfirst(str_replace('_', ' ', $event_single_group[$keyx]['type'])),
															);
														}
													}
												}

											

												
												foreach ($aditionals_events as $keye => $valuee) { 
													//print_r($event[$key][$keyx]);
													?>
													<option value="<?php echo $valuee['type']; ?>" <?php if($event[$key][$keyx]['type']===$valuee['type']) {echo "selected";}   ?> > 
														<?php echo $valuee['title']; ?>
													</option>
													<?php 
												} 

												?>
											</select>
										</label>
									</td>
									<td>
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td><?php _e('Date', 'genealogical-tree'); ?></td>
												<td><input type="text" id="schooling" value="<?php echo $value['date']; ?>" name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][date]"></td>
											</tr>
											<tr>
												<td><?php _e('Place', 'genealogical-tree'); ?></td>
												<td>
													<input type="text" id="schooling" value="<?php echo isset($value['place']) ? $value['place'] : ''; ?>" name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][place]">
													<input type="hidden" id="schooling" name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][ref]">
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>

			<?php 
			$xc++;
		 }
		  ?>

		  <?php
			 $yc++;
			} 

		?>
					</td>
				</tr>


			</table>
		</div>
	</div>
</div>