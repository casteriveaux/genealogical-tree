<button class="close"><i class="fa fa-times" aria-hidden="true"></i></button>

<h2 style="margin:0"><%= plac.zoom[0].value %></h2>
	
<% _.each(plac.zoom, function(zoom,i) { %>
	<% if (i>0) { %>
		<a href="./?plac=<%= zoom.ref %>" data-plac="<%= zoom.ref %>" target="plac"><%= zoom.value %></a><% if (i != plac.zoom.length -1) { %>, <% } %> 
	<% } %>
<% }); %>
	
<ul class="nav nav-pills nav-justified" style="margin-top:10px">
  <li class="active"><a data-toggle="pill" href="#tabIndi">People</a></li>
  <li><a data-toggle="pill" href="#tabPlac">Places</a></li>
</ul>