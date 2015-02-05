<head>
    <title>edit-empty</title>
    <link rel="stylesheet" type="text/css" href="templates/ws-edit-empty/style.css"/>
</head>
<body>

	<form action="javascript:void(0);" class="forms">
        <fieldset>
            <legend id="editlegend"></legend>
            <ul>
                <li>
                    <ul class="multicolumn">
                        <li>
                            <label for="title" class="bold">Titel</label>
                            <input id="title" type="text" name="title" required placeholder="Titel" value="">
                        </li>
                        <li>
                            <label>&nbsp;</label>
                            <label for="visiblecheckbox"><input id="visiblecheckbox" class="visiblecheckbox"
                                                                type="checkbox" name="visible"/> Auf der
                                Webseite anzeigen</label>
                        </li>
                    </ul>
                </li>
                <li class="main-editor-li">
                    <textarea name="content"></textarea>
                </li>
                <li>
                    <ul class="row">
                        <li class="third">
                            <button type="submit" id="submitbutton" class="btn greenbtn"><i
                                    class="icon-pencil"></i> Speichern
                            </button>
                        </li>
                        <li class="third" id="leveloption">
            <span class="btn-group">
              <button id="leveldown" class="btn .btn-prepend"><i class="icon-angle-left"></i></button>
              <span class="btn disabled" id="level">Ebene <span id="levelcount"></span></span>
              <button id="levelup" class="btn .btn-append"><i class="icon-angle-right"></i></button>            
            </span>
                        </li>

                        <li class="push-right">
                            <input type="hidden" id="entryId" value="">
                            <button type="submit" id="deletebutton" class="btn redbtn"
                                    name="deletebutton"></button>
                        </li>
                    </ul>
                </li>
            </ul>
        </fieldset>
    </form>

</body>