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
						<h4>Name</h4>
					</td>
				</tr>
				<tr>
					<td>
						<label for="full-name">Full Name</label>
					</td>
					<td>
						<input id="full-name" type="text" name="gt[full_name]" value="<?php echo $full_name; ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="given-name">Given Name</label>
					</td>
					<td>
						<input id="given-name" type="text" name="gt[given_name]" value="<?php echo $given_name; ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="sur-name">Surname</label>
					</td>
					<td>
						<input id="surname" type="text" name="gt[surname]" value="<?php echo $surname; ?>">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h4>Gender</h4>
					</td>
				</tr>
				<tr>
					<td>
						<label for="birth-sex">Gender</label>
					</td>
					<td>
						<select id="birth-sex" name="gt[sex]">
							<option value="">Select Gender</option>
							<option value="M" <?php echo ($sex==='M') ? 'selected' : '' ; ?>>Male</option>
							<option value="F" <?php echo ($sex==='F') ? 'selected' : '' ; ?>>Female</option>
						</select>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<h4>Birth</h4>
					</td>
				</tr>
			<?php $bc = 1; foreach ($event['birt'] as $key => $value) { 

				?>

				<tr>
					<td>
						REF #<?php //echo $bc; ?>
					</td>
					<td>
						<div style="margin-left: -3px; margin-right: -3px;">

						<table>
							<tr>
								<td>
									<label for="birt-date">Date</label>
								</td>
								<td>
									<input id="birt-date" type="text" name="gt[event][birt][<?php echo $key; ?>][date]"  value="<?php echo $value['date']; ?>">
								</td>
							</tr>
							<tr>
								<td>
									<label for="birt-place">Place</label>
								</td>
								<td>
									<input id="birt-place" type="text" name="gt[event][birt][<?php echo $key; ?>][place]" value="<?php echo $value['place']; ?>">
									<input id="birt-ref" type="hidden" name="gt[event][birt][<?php echo $key; ?>][ref]" ">
								</td>
							</tr>
						</table>
					</div>
					</td>
				</tr>




			<?php  

		$bc++; } ?>
				<tr>
					<td colspan="2">
						<h4>Death</h4>
					</td>
				</tr>
			<?php $dc = 1; foreach ($event['deat'] as $key => $value) { ?>


				<tr>
					<td>
						REF #<?php // echo $dc; ?>
					</td>
					<td>
						<div style="margin-left: -3px; margin-right: -3px;">

						<table>
							<tr>
								<td>
									<label for="deat-date">Date</label>
								</td>
								<td>
									<input id="deat-date" type="text" name="gt[event][deat][<?php echo $key; ?>][date]" value="<?php echo $value['date']; ?>">
									<br><small><i>Leave empty if still alive</i></small>
								</td>
							</tr>
							<tr>
								<td>
									<label for="deat-place">Place</label>
								</td>
								<td>
									<input id="deat-place" type="text" name="gt[event][deat][<?php echo $key; ?>][place]" value="<?php echo $value['place']; ?>">
									<input id="deat-ref" type="hidden" name="gt[event][deat][<?php echo $key; ?>][ref]" ">

								</td>
							</tr>
						</table>
					</div>
					</td>
				</tr>





			<?php  $dc++; } ?>
			</table>
		</div>
		<div class="gta-col-3">
			<table class="gta-table">
				<tr>
					<td colspan="2">
						<h4>Parents</h4>
					</td>
				</tr>
				<tr>
					<td>
						<label for="mother">Mother</label>
					</td>
					<td>
						<select id="mother" name="gt[mother]">
							<option value="">Select Mother</option>
							<?php foreach ($mothers as $key => $value) { ?>
								<option <?php if($value==$mother) { echo 'selected'; } ?> value="<?php echo $value; ?>"><?php echo get_post_meta($value, 'full_name', true); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<label for="father">Father</label>
					</td>
					<td>
						<select id="father" name="gt[father]">
							<option value="">Select Father</option>
							<?php foreach ($fathers as $key => $value) { ?>
								<option <?php if($value==$father) { echo 'selected'; } ?> value="<?php echo $value; ?>"><?php echo get_post_meta($value, 'full_name', true); ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h4>Spouses</h4>
					</td>
				</tr>
				<?php $y = 0; foreach ( $spouses as $key => $wife) { ?>
				<tr class="tr-wife" style="<?php if($sex==='F'){ echo 'display: none;'; } elseif($sex==='M') { echo 'display: table-row;'; } else {echo 'display: table-row;';} ?>">
					<td>
						<label for="wife">Wife</label>
					</td>
					<td>
						<div class="repetead-field"> <span class="clone">Add</span>
							<select id="wife" name="gt[wife][<?php echo $y; ?>][id]">
								<option value="">Select Wife</option>
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
					</td>
				</tr>
				<?php $y++;}  ?>
				<?php $x = 0;  foreach ( $spouses as $key => $husb) { ?>
				<tr class="tr-husb" style="<?php if($sex==='F'){ echo 'display: table-row;'; } elseif($sex==='M') { echo 'display: none;'; } else {echo 'display: table-row;';} ?>">
					<td>
						<label for="husb">Husband</label>
					</td>
					<td>
						<div class="repetead-field"> <span class="clone">Add</span>
							<select id="husb" name="gt[husb][<?php echo $x; ?>][id]">
								<option value="">Select Husband</option>
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
					</td>
				</tr>
				<?php $x++; } ?>

				<tr>
					<td colspan="2">
						<h4>Contact Information</h4>
					</td>
				</tr>
				<?php foreach ($phone as $key => $phon) { ?>
				<tr>
					<td>
						<label for="phone">Phone</label>
					</td>
					<td>
						<div class="repetead-field"> <span class="clone">Add</span>
							<input type="text" id="phone" name="gt[phone][<?php echo $key; ?>]" value="<?php echo $phon; ?>">
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php foreach ($email as $key => $emai) { ?>
				<tr>
					<td>
						<label for="email">Email</label>
					</td>
					<td>
						<div class="repetead-field"> <span class="clone">Add</span>
							<input type="text" id="email" name="gt[email][<?php echo $key; ?>]"  value="<?php echo $emai; ?>">
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php foreach ($address as $key => $addr) { ?>
				<tr>
					<td>
						<label for="address">Address</label>
					</td>
					<td>
						<div class="repetead-field"> <span class="clone">Add</span>
							<input type="text" id="address" name="gt[address][<?php echo $key; ?>]"  value="<?php echo $addr; ?>">
						</div>
					</td>
				</tr>
				<?php } ?>

			</table>
		</div>

		<?php 
		unset($event['birt']);
		unset($event['deat']);

		if(empty($event)){
			$event[0][0] = array(
				'date' => '', 
				'place' => ''
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

		);
		?>

		<div class="gta-col-3">
			<table class="gta-table">
				<tr>
					<td>
						<h4>Additional Events</h4>
					</td>
				</tr>
				<?php 
				$yc = 0; 
				foreach ($event as $key => $event_single_group) {  
					$xc = 0; 
					foreach ($event_single_group as $keyx => $value) {
				?>
				<tr>
					<td>
						<div class="repetead-field"> <span class="clone">Add</span>
							<table style="width: 100%">
								<tr>
									<td>
										<label style="padding-right: 20px;" for="schooling">
											<select name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][type]">
												<option value="0">Select an Event</option>
												<?php foreach ($aditionals_events as $keye => $valuee) { ?>
													
													<option value="<?php echo $valuee['type']; ?>" <?php if(isset($aditionals_events[$key]['type'])) { if($aditionals_events[$key]['type']===$valuee['type']) {echo "selected";} } ?> > <?php echo $valuee['title']; ?></option>
												<?php } ?>
											</select>
										</label>
									</td>
									<td>
										<table style="width: 100%">
											<tr>
												<td>Date</td>
												<td><div  style="width: 160px">
													
												</div> <input type="text" id="schooling" value="<?php echo $value['date']; ?>" name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][date]"></td>
											</tr>
											<tr>
												<td>Place</td>
												<td>
													<input type="text" id="schooling" value="<?php echo $value['place']; ?>" name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][place]">
													<input type="hidden" id="schooling" name="gt[event][<?php echo $yc; ?>][<?php echo $xc; ?>][ref]">
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			<?php 
		$yc++; }
			$xc++; 
			} 

		?>



			</table>
		</div>
	</div>
</div>