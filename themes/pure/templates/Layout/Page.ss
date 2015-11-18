<% include Breadcrumbs %>
<div class="pure-g pure-g-padding">
	<% if Menu(2) %>
		<% include SecondaryMenu %>
	<% end_if %>
	<div class="pure-u-1 <% if Menu(2) %>pure-u-md-3-4<% end_if %>">
		<div class="main typography" role="main" id="main">
			<h1 class="page-header">$Title</h1>
			$Content
			$Form

			<% if $CurrentMember %>
				<div class="one">waiting...</div>
				<div class="two">waiting...</div>
				<div class="three">waiting...</div>
			<% else %>
				please login to check session
			<% end_if %>

			<% include RelatedPages %>
			$PageComments
		</div>
		<% include ContentFooter %>
	</div>
</div>

