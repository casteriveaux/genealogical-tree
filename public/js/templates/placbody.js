<div class="tab-content" style="padding:15px">

  <div role="tabpanel" class="tab-pane fade in active" id="tabIndi">


		<table class="table" id="plac-indi-table">
		
			<thead>
				<tr>
					<th>Name</th>
					<th>Date</th>
				</tr>
			</thead>	
		
		<% _.each(plac.indi, function(indi,i) { %>
			<% if (indi.type == "indi" || indi.type == "fam") { %>
				<tr data-indi="<%= indi.ref %>">
					<td>
					
						<% if (indi.name.first == "...") { %>
							<span style="display:none">~</span>
						<% } %>
									
						<%= indi.name.first %> <%= indi.name.last %>
					</td>
					<td>
						<% if (indi.year == "9999") { %>
							<span style="display:none">~</span>
						<% } else { %>
							<%= indi.year %>
						<% } %>
					</td>
				</tr>
			<% } %>
		<% }); %>
		
		</table>

  
  </div>

  <div role="tabpanel" class="tab-pane fade" id="tabPlac">


		<table class="table" id="plac-plac-table">
		
			<thead>
				<tr>
					<th>Name</th>
				</tr>
			</thead>	
		
		<% _.each(plac.zoomin, function(zoomin,i) { %>
			<tr data-plac="<%= zoomin.ref %>">
				<td>
					<%= zoomin.value %>
				</td>
			</tr>
		<% }); %>
		
		</table>
		
  
  </div>
  
</div>
