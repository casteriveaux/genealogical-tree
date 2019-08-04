<div class="cg-sidebar-header"><h2>direct relations...</h2></div>
<div class="listheader" data-toggle="collapse" data-target=".people">People<span class="icon-chevron-down icon-white pull-right"></span><span class="icon-chevron-right icon-white pull-right"></span></div>
<ul class="cg-sidebar people in collapse" style="height: auto;">
<% _.each(names, function(child) { %>
<li><a rel="content" href="./?indi=<%= child.id %>"><%= child.first %><% if (child.last!='...') { %> <%= child.last %><% } %></a><% if (child.year!='') { %> (b. <%= child.year %>)<% } %></li>
<% }); %>
<li><p><b><%= names.length %></b> records</p></li>
</ul>