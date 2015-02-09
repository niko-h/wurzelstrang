<form id="prefsite" action="javascript:void(0);" class="forms columnar fullwidth">
    <fieldset>
        <legend>Seiten Informationen</legend>
        <ul>
            <li>
                <label for="sitetitle" class="bold">Seitentitel</label>
                <input name="sitetitle" id="sitetitle" class="" value="" type="text">
            </li>
            <li>
                <label for="headline" class="bold">&Uuml;berschrift</label>
                <input name="siteheadline" id="siteheadline" class="" value="" type="text">
            </li>
            <li>
                <label for="theme" class="bold">Theme</label>
                <select name="sitetheme" id="sitetheme" class="select">
                    <?php foreach( $themes as $theme ) {
                        echo '<option>' . $theme . '</option>\n';
                    } ?>
                </select>
            </li>
            <li>
                <label for="levels" class="bold">Pseudohierarchien</label>
  <span class="btn-group" id="levels" data-toggle="buttons" data-target="#levelstarget">
    <button class="btn" value="1">On</button>
    <button class="btn" value="0">Off</button>
  </span>
                <input type="text" style="display:none;" id="levelstarget"/>

                <div class="descr">Kann zur Einrückung der Menüeinträge verwendet werden um diese
                    optisch zu sortieren.
                </div>
            </li>
            <li class="push">
                <input name="submitsiteinfobtn" id="updatesiteinfobtn" class="btn greenbtn"
                       value="Seite aktualisieren" type="submit">
            </li>
        </ul>
    </fieldset>
</form>