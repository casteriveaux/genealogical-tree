  <% 
    var partner;
    
    if (indiId == fam.wife.ref)
      partner = fam.husb;
    else
      partner = fam.wife;
    
  %>
  <tr data-indi="<%= partner.ref %>">
  
  <td>
    <%= partner.name.first %> <%= partner.name.last %>
  </td>
  
  <td>  
    <% 
        var birt = _.findWhere(partner.event, {'type' : 'birt'});
        
        var birtTxt = "";

        if (birt && birt.date)
          birtTxt = birt.date.value;
        
      %>
      
      <%=birtTxt%>
  </td>  

	</tr>
  
  
  <% if (fam.children) { %>
  
      <% _.each(fam.children, function(child) { %>

        <tr data-indi="<%= child.ref %>">

			<td>
			
            <%= child.name.first %> <%= child.name.last %>
			
			</td>
			
			<td>
            
            <% 
                var birt = _.findWhere(child.event, {'type' : 'birt'});
                
                var birtTxt = "";

                if (birt && birt.date)
                  birtTxt = birt.date.value;
                
              %>
                
              <%=birtTxt%>
                
			</td>
			
        </tr>
                  
      <% }); %>
        
  <% } %>
