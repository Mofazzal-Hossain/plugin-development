QTags.addButton("quicktags-underline", "Underline", "<u>", "</u>");
QTags.addButton("quicktags-name", "Name", quicktags_name_function);
QTags.addButton(
  "quicktags-fontawesome",
  "Fontawesome",
  quicktags_fontawesome_add
);
QTags.addButton("quicktags-bangla", "Bangla", quicktags_phonetic_bangla);
QTags.addButton("quicktags-english", "English", quicktags_phonetic_english);

// user input name
function quicktags_name_function() {
  var name_input = prompt("Enter your name");
  var text = "Hello " + name_input;
  QTags.insertContent(text);
}

// onclick show wp popup/thickbox
function quicktags_fontawesome_add() {
  tb_show("fontawesome", customicons.preview);
}

// insert fontawesome icon
function insertIcon(icon) {
  tb_remove("fontawesome", customicons.preview);
  var icon = '<i class="fa-brands ' + icon + '"></i>';
  console.log(icon);
  QTags.insertContent(icon);
}


// phonetic bangla
var phkb_init = false;
function quicktags_phonetic_bangla() {
    if(!phkb_init){
        jQuery('.wp-editor-area').bnKb({
            'switchkey':'b',
            'driver':phonetic
        });
        phkb_init = true;
    } else {
        jQuery('.wp-editor-area').bnKb.sw('b');
    }
}
//phonetic english
function quicktags_phonetic_english() {
    jQuery('.wp-editor-area').bnKb.sw('e');
}
