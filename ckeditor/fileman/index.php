<?php
session_start();
if(empty($_SESSION))
{
	@session_destroy();
	include_once(realpath('./php/system_portal.php'));
}
define('FILEPATH',  $_SESSION['pmx_ckfm']['FILEPATH']);
if(!empty( $_SESSION['pmx_ckfm']) && (is_array( $_SESSION['pmx_ckfm']) && !empty( $_SESSION['pmx_ckfm']['ALLOW']) && !empty( $_SESSION['pmx_ckfm']['FILEPATH'])))
	echo '<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Image manager</title>
<link href="css/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css" />
<link href="css/main.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/main.min.js"></script>
<script type="text/javascript" src="js/jquery-dateFormat.min.js"></script>
</head>
<body onload="this.resizeBy(-300,-60);this.moveBy(150,0)">
<div id="bodydiv" style="overflow:hidden;">
<table id="wraper">
	<tr>
	 <td valign="top" class="pnlDirs" id="dirActions">
		<div class="actions">
			<input type="button" id="btnAddDir" value="Create" title="Create new directory" onclick="addDir()" data-lang-v="CreateDir" data-lang-t="T_CreateDir" />
			<input type="button" id="btnRenameDir" value="Rename" title="Rename directory" onclick="renameDir()" data-lang-v="RenameDir" data-lang-t="T_RenameDir" />
			<input type="button" id="btnDeleteDir" value="Delete" title="Delete selected directory" onclick="deleteDir()" data-lang-v="DeleteDir" data-lang-t="T_DeleteDir" />
		</div>
		<div id="pnlLoadingDirs">
			 <span>Loading directories...</span><br>
			 <img src="images/loading.gif" title="Loading directory tree, please wait...">
		</div>
		<div class="scrollPane">
			<ul id="pnlDirList"></ul>
		</div>
	 </td>
	 <td valign="top" id="fileActions">
	 <input type="hidden" id="hdViewType" value="list">
	 <input type="hidden" id="hdOrder" value="asc">
		<div class="actions">
			<input type="button" id="btnAddFile" value="Add file" title="Upload files" onclick="addFile()" data-lang-v="AddFile" data-lang-t="T_AddFile" />
			<input type="button" id="btnRenameFile" value="Rename" title="Rename selected file" onclick="renameFile()" data-lang-v="RenameFile" data-lang-t="T_RenameFile" />
			<input type="button" id="btnDownloadFile" value="Download" title="Download selected file" onclick="downloadFile()" data-lang-v="DownloadFile" data-lang-t="T_DownloadFile" />
			<input type="button" id="btnDeleteFile" value="Delete" title="Delete selected file" onclick="deleteFile()" data-lang-v="DeleteFile" data-lang-t="T_DeleteFile" />
			<input type="button" id="btnSelectFile" value="Select" title="Select highlighted file" onclick="setFile()" data-lang-v="SelectFile" data-lang-t="T_SelectFile" />
			<br>
			Order by:
			<select id="ddlOrder" onchange="sortFiles()">
				<option value="name" data-lang="Name_asc">&uarr;&nbsp;&nbsp;Name</option>
				<option value="size" data-lang="Size_asc">&uarr;&nbsp;&nbsp;Size</option>
				<option value="time" data-lang="Date_asc">&uarr;&nbsp;&nbsp;Date</option>
				<option value="name_desc" data-lang="Name_desc">&darr;&nbsp;&nbsp;Name</option>
				<option value="size_desc" data-lang="Size_desc">&darr;&nbsp;&nbsp;Size</option>
				<option value="time_desc" data-lang="Date_desc">&darr;&nbsp;&nbsp;Date</option>
			</select>&nbsp;&nbsp;
			<input type="button" id="btnListView" class="btnView" value=" " title="List view" onclick="switchView(\'list\')" data-lang-t="T_ListView" />
			<input type="button" id="btnThumbView" class="btnView" value=" " title="Thumbnails view" onclick="switchView(\'thumb\')" data-lang-t="T_ThumbsView" />&nbsp;&nbsp;
			<input type="text" id="txtSearch" onkeyup="filterFiles()" onchange="filterFiles()" />
		</div>
		<div class="pnlFiles">
			 <div class="scrollPane">
				 <div id="pnlLoading">
					<span data-lang="LoadingFiles">Loading files...</span><br>
					<img src="images/loading.gif" title="Loading files, please wait...">
					</div>
					<div id="pnlEmptyDir" data-lang="DirIsEmpty">
					This folder is empty
					</div>
					<div id="pnlSearchNoFiles" data-lang="NoFilesFound">
					No files found
					</div>
				 <ul id="pnlFileList"></ul>
			 </div>
		</div>
	 </td>
	</tr>
</table>
</div>
<!-- Forms and other components -->
<iframe name="frmUploadFile" width="0" height="0" style="display:none;border:0;"></iframe>
<div id="dlgAddFile">
	<form name="addfile" id="frmUpload" method="post" target="frmUploadFile" enctype="multipart/form-data">
		<input type="hidden" name="action" value="upload" />
		<input type="hidden" name="d" id="hdDir" />
		<div class="form">
			Select files to upload<br />
			<input type="file" name="files[]" id="fileUploads" multiple="multiple" />
		</div>
	</form>
</div>
<div id="menuFile" class="contextMenu">
	<a href="#" onclick="setFile()" data-lang="SelectFile" id="mnuSelectFile">Select</a><hr>
	<a href="#" onclick="downloadFile()" data-lang="DownloadFile" id="mnuDownload">Download</a><hr>
	<a href="#" onclick="return pasteToFiles(event, this)" data-lang="Paste" class="paste pale" id="mnuFilePaste">Paste</a><hr>
	<a href="#" onclick="cutFile()" data-lang="Cut" id="mnuFileCut">Cut</a><hr>
	<a href="#" onclick="copyFile()" data-lang="Copy" id="mnuFileCopy">Copy</a><hr>
	<a href="#" onclick="renameFile()" data-lang="RenameFile" id="mnuRenameFile">Rename</a><hr>
	<a href="#" onclick="deleteFile()" data-lang="DeleteFile" id="mnuDeleteFile">Delete</a><!-- hr>
	<a href="#" onclick="fileProperties()" id="mnuProp">Properties</a -->
</div>
<div id="menuDir" class="contextMenu">
	<a href="#" onclick="downloadDir()" data-lang="Download" id="mnuDownloadDir">Download</a><hr>
	<a href="#" onclick="addDir()" data-lang="T_CreateDir" id="mnuCreateDir">Create new</a><hr>
	<a href="#" onclick="return pasteToDirs(event, this)" data-lang="Paste" class="paste pale" id="mnuDirPaste">Paste</a><hr>
	<a href="#" onclick="cutDir()" data-lang="Cut" id="mnuDirCut">Cut</a><hr>
	<a href="#" onclick="copyDir()" data-lang="Copy" id="mnuDirCopy">Copy</a><hr>
	<a href="#" onclick="renameDir()" data-lang="RenameDir" id="mnuRenameDir">Rename</a><hr>
	<a href="#" onclick="deleteDir()" data-lang="DeleteDir" id="mnuDeleteDir">Delete</a>
</div>
<div id="pnlRenameFile" class="dialog">
	<span class="name"></span><br>
	<input type="text" id="txtFileName">
</div>
<div id="pnlDirName" class="dialog">
	<span class="name"></span><br>
	<input type="text" id="txtDirName">
</div>
</body>
<script>document.getElementById("bodydiv").style.height = (window.innerHeight-67)+"px";</script>
</html>';
else
	echo 'Access denied';
?>