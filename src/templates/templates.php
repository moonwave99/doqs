<script type="text/template" id="folder-doc">
	<tr data-path="<%=doc.path%>">
		<td>
			<i class="icon-file"></i> <a href="<%=basePath+doc.path%>"><%=doc.name%></a>
		</td>
		<td>
			<a href="#" class="btn btn-mini btn-inverse" data-controller="folder" data-action="editDoc">
				<i class="icon-pencil icon-white"></i>
			</a>
			<a href="#" class="btn btn-mini btn-inverse" data-controller="folder" data-action="deleteDoc">
				<i class="icon-trash icon-white"></i>
			</a>
		</td>
	</tr>
</script>

<script type="text/template" id="folder-folder">
	<tr data-path="<%=folder.path%>">
		<td>
			<i class="icon-folder-close"></i> <a href="<%=basePath+folder.path%>"><%=folder.name%></a>
		</td>
		<td>
			<a href="#" class="btn btn-mini btn-inverse" data-controller="folder" data-action="editFolder">
				<i class="icon-pencil icon-white"></i>
			</a>
			<a href="#" class="btn btn-mini btn-inverse" data-controller="folder" data-action="deleteFolder">
				<i class="icon-trash icon-white"></i>
			</a>
		</td>
	</tr>
</script>

<script type="text/template" id="folder-folder-parent">
	<tr>
		<td>
			<i class="icon-arrow-up"></i> <a href="<%=basePath+folder.path%>">..</a>
		</td>
		<td></td>
	</tr>
</script>

<script type="text/template" id="folder-new-doc">
	<tr>
		<td>
			<i class="icon-file"></i>
			<form data-controller="folder" data-action="createDoc" class="homepage-form">
				<input type="text" name="resName" placeholder="Choose a filename" required/>
				<span class="label label-important"></span>
			</form>
		</td>
		<td>
			<a href="#" class="btn btn-mini btn-inverse" data-controller="folder" data-action="createCancel">
				<i class="icon-remove icon-white"></i> Cancel
			</a>
		</td>
	</tr>
</script>
<script type="text/template" id="folder-new-folder">
	<tr>
		<td>
			<i class="icon-folder-close"></i>
			<form data-controller="folder" data-action="createFolder" class="homepage-form">
				<input type="text" name="resName" placeholder="Choose a folder name" required/>
				<span class="label label-important"></span>
			</form>
		</td>
		<td>
			<a href="#" class="btn btn-mini btn-inverse" data-controller="folder" data-action="createCancel">
				<i class="icon-remove icon-white"></i> Cancel
			</a>
		</td>
	</tr>
</script>

<script type="text/template" id="folder-edit-doc">
	<tr data-path="<%=path%>">
		<td>
			<i class="icon-file"></i>
			<form data-controller="folder" data-action="saveDoc" class="homepage-form">
				<input type="text" name="resName" placeholder="Choose a filename"  value="<%=name%>" required/>
				<span class="label label-important"></span>
			</form>
		</td>
		<td>
			<a href="#" class="btn btn-mini btn-inverse" data-controller="folder" data-action="editCancel">
				<i class="icon-remove icon-white"></i> Cancel
			</a>
		</td>
	</tr>
</script>
<script type="text/template" id="folder-edit-folder">
	<tr data-path="<%=path%>">
		<td>
			<i class="icon-folder-close"></i>
			<form data-controller="folder" data-action="saveFolder" class="homepage-form">
				<input type="text" name="resName" placeholder="Choose a folder name" value="<%=name%>" required/>
				<span class="label label-important"></span>
			</form>
		</td>
		<td>
			<a href="#" class="btn btn-mini btn-inverse" data-controller="folder" data-action="editCancel">
				<i class="icon-remove icon-white"></i> Cancel
			</a>
		</td>
	</tr>
</script>