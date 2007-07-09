<div>
	<h3>{$doc.header}</h3>
	<p>Rating: {$doc.rating}, votes: {$doc.votes}</p>
	{if $ctx->auth->userId > 0}
		{if $doc.rateable}
			<form method="POST" action="/page-with-ratingratelogic">
				<input type="hidden" name="f_docId" value="{$doc.id}">
				<p>
					Your rating:<br>
					<input type="text" name="f_rating" value="{$smarty.get.f_rating|escape}">
				</p>
				<p>
					Comment(optional):<br>
					<textarea name="f_comment">{$smarty.get.f_comment|escape}</textarea>
				</p>
				<p><input type="submit"></p>
			</form>
		{else}
			<p>You've already voted for this document. The rating you gave was {$doc.userRating}</p>
		{/if}
	{else}
		<p>You moust be logged in to vote</p>
	{/if}
</div>
