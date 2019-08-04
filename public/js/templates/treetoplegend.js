
        <% if (indi.father) { %>
        <tr data-indi="<%= indi.father.ref %>">

			<td>
          
				<%= indi.father.name.first %>&#160;<%= indi.father.name.last %> 
			
			</td>
			
			<td>

				<% 
				  var birt = _.findWhere(indi.father.event, {'type' : 'birt'});
				
				  var birtTxt = "";

				  if (birt)
					birtTxt = birt.date.value.substring(0,4);
				
				%>
				
				<%=birtTxt%>
			
			</td>
          
        </tr>
        
      <% } %>
      <% if (indi.mother) { %>

        <tr data-indi="<%= indi.mother.ref %>">
        
			<td>
			
				<%= indi.mother.name.first %>&#160;<%= indi.mother.name.last %> 
				
			</td>

			<td>

				<% 
				  var birt = _.findWhere(indi.mother.event, {'type' : 'birt'});
				
				  var birtTxt = "";

				  if (birt)
					birtTxt = birt.date.value.substring(0,4);
				
				%>
				
				<%=birtTxt%>
				
			</td>

        </tr>

      <% } %>
        
    <tr data-indi="<%= indi.id %>">
        
		<td>
		
			<%= indi.name %>
		
		</td>
        
		<td>
		
        <% 
          var birt = _.findWhere(indi.event, {'type' : 'birt'});
        
          var birtTxt = "";

          if (birt)
            birtTxt = birt.date.value.substring(0,4);
          
        %>
        
        <%=birtTxt%>
		
		</td>
            
    </tr>