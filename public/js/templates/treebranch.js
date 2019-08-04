<div class="p">
	<% 
		var partner;
		if (indiId == fam.wife.ref)
			partner = fam.husb;
		else
			partner = fam.wife;
	%>
	<div data-indi="<%=partner.ref%>" class="indi <%=partner.sex.toLowerCase()%> <% if (partner.ref == fam.hilight) { %>active<% } %>">
		<% if (partner.contact=='Y') { %>
			<span style="position:absolute; top:-7px; right:-7px;">
				<i class="icon-star"></i>
			</span>
		<% } %>
		<div class="tree-thumbnail">
			<% if (fb==="on" && partner.fb) { %>
					<img src="http://graph.facebook.com/<%= partner.fb %>/picture" class="img-rounded" />
			<% } else if (tw==="on" && partner.tw) { %>
					<img src="https://api.twitter.com/1/users/profile_image?size=bigger&amp;screen_name=<%= partner.tw %>" class="img-rounded" />
			<% } else if (partner.image) { %>
					<img src="<%= partner.image %>" class="img-rounded" />
			<% } %>
		</div>
		<div class="tree-detail"><%= partner.name.first %> <% if (partner.name.last!='...') { %><%= partner.name.last %> <% } else { %> &nbsp; <% } %> 
		<% 
			var birt = _.findWhere(partner.event, {'type' : 'birt'});
			var deat = _.findWhere(partner.event, {'type' : 'deat'});
			var birtTxt = "";
			var deatTxt = "";
			if (birt && birt.date)
				birtTxt = birt.date.value;
			if (deat && deat.date)
				deatTxt = deat.date.value;
			%>
			<% if ((birt && birt.date) || (deat && deat.date)) { %>
				<span>(<%=birtTxt%> - <%=deatTxt%>)</span>
			<% } else { %>
				<span>&nbsp;</span>
			<% } %>
		</div>
	</div>
	<% if (fam.children) { %>
		<ul class="c">
			<% _.each(fam.children, function(child) { %>
				<li>
					<div data-indi="<%=child.ref%>" rel="content" class="indi <%=child.sex.toLowerCase()%> <% if (child.ref == fam.hilight) { %>active<% } %>">
						<% if (child.contact=='Y') { %>
							<span style="position:absolute; top:-7px; right:-7px;">
								<i class="icon-star"></i>
							</span>
						<% } %>
						<div class="tree-thumbnail">
							<div class="tree-thumbnail">
								<% if (fb==="on" && child.fb) { %>
										<img src="http://graph.facebook.com/<%= child.fb %>/picture" class="img-rounded" />
								<% } else if (tw==="on" && child.tw) { %>
										<img src="https://api.twitter.com/1/users/profile_image?size=bigger&amp;screen_name=<%= child.tw %>" class="img-rounded" />
								<% } else if (child.image) { %>
										<img src="<%= child.image %>" class="img-rounded" />
								<% } %>
							</div>
						</div>
						<div class="tree-detail"><%= child.name.first %> <% if (child.name.last!='...') { %><%= child.name.last %> <% } else { %> &nbsp; <% } %> 
						<% 
							var birt = _.findWhere(child.event, {'type' : 'birt'});
							var deat = _.findWhere(child.event, {'type' : 'deat'});
							var birtTxt = "";
							var deatTxt = "";
							if (birt && birt.date)
								birtTxt = birt.date.value;
							if (deat && deat.date)
								deatTxt = deat.date.value;
							%>
							<% if ((birt && birt.date) || (deat && deat.date)) { %>
								<span>(<%=birtTxt%> - <%=deatTxt%>)</span>
							<% } else { %>
								<span>&nbsp;</span>
							<% } %>
						</div>
					</div>
					<ul class="p">
						<% _.each(_.where(child.event, {'type' : 'partner'}), function(partner) { %>
						<li id="fam_<%=partner.ref%>" class="pending">
							<a href="./branch=<%=partner.ref%>/<%=child.ref%>/" target="branch" data-branch="<%=partner.ref%>" data-indi="<%=child.ref%>">
								<i class="dashicons dashicons-arrow-up-alt2"></i><!--<%=partner.ref%>-->
							</a>
						</li>
						<% treeBranch(partner.ref,child.ref,fam.hilight) %>
						<% }); %>
					</ul>
				</li>
			<% }); %>
		</ul>
	<% } %>
</div>