(function($){
    $(document).ready(function(){
        tinymce.PluginManager.add('visualmce_plugins', function(editor, url){
           
            editor.addButton('visualmce_custom_button', {
                text: 'Smile',
                // icon: 'icon dashicons-smiley',
                image: url + '/../images/smile.png',
                classes: 'custom-btn',
                onclick: function(){
                    editor.insertContent('Hello world');
                }
            });

            editor.addButton('visualmce_custom_button2',{
                text: 'button 2',
                icon: 'dashicons-editor-break',
                onclick: function (){
                    editor.insertContent('custom button 2');
                }
            });

            editor.addButton('visualmce_select_country', {
                text:'Select a Country',
                type:'listbox',
                values: [
                    {text:'Bangladesh',value:'Your selected country is <b>Bangladesh</b>'},
                    {text:'India',value:'Your selected country is <b>India</b>'},
                    {text:'Australia',value:'Your selected country is <b>Australia</b>'},
                    {text:'China',value:'Your selected country is <b>China</b>'},
                ],
                onselect: function(){
                    editor.insertContent(this.value());
                },
                onPostRender: function() {
                    this.value('Your selected country is <b>Australia</b>');
                }
            });

            editor.addButton('visualmce_menu_list', {
                text: 'Choices',
                type: 'listbox',
                values: [
                    {
                        text: 'Option 1',
                        onclick: function(){
                            editor.insertContent('Option 1');
                        },
                        menu: [
                            {
                                text: 'Option 1 - A',
                                onclick: function(){
                                    editor.insertContent('Option 1 - A');
                                }
                            },
                            {
                                text: 'Option 1 - B',
                                onclick: function(){
                                    editor.insertContent('Option 1 - B');
                                }
                            },
                            {
                                text: 'Option 1 - C',
                                onclick: function(){
                                    editor.insertContent('Option 1 - C');
                                }
                            }

                        ]
                    },
                    {
                        text: 'Option 2',
                        onclick: function(){
                            editor.insertContent('Option 2');
                        },
                        menu: [
                            {
                                text: 'Option 2 - A',
                              
                                onclick: function(){
                                    editor.insertContent('Option 2 - A');
                                }
                            },
                            {
                                text: 'Option 2 - B',
                                onclick: function(){
                                    editor.insertContent('Option 2 - B');
                                }
                            },
                            {
                                text: 'Option 2 - C',
                                onclick: function(){
                                    editor.insertContent('Option 2 - C');
                                }
                            }

                        ]
                    }
                ]
            });

            editor.addButton('visualmce_custom_form', {
                text: 'Form',
                // onclick: function(){
                //     tb_show('customform', customform.preview);
                // }
                onclick: function(){
                    editor.windowManager.open({
                        title: 'User input form',
                        body: [
                            {
                                type: 'textbox',
                                name: 'user_name',
                                label: 'User name',
                                value: "Hello",
                            },
                            {
                                type: 'colorpicker',
                                name: 'color_picker',
                                label: 'Color picker',
                                value: "#222",
                            },
                            {
                                type: 'listbox',
                                name: 'country',
                                label: 'Select a country',
                                values: [
                                    {text: "Bangladesh", value: "Bangladesh"},
                                    {text: "India", value: "India"},
                                    {text: "Nepal", value: "Nepal"},
                                    {text: "Australia", value: "Australia"},
                                    {text: "Japan", value: "Japan"}
                                ]
                            }
                        ],
                        onsubmit: function(e){
                            console.log(e.data.color_picker);
                            console.log(e.data.user_name);
                            var data  = e.data;
                            var username = 'Username is: ' + data.user_name + '<br>';
                            var colorpick = 'Color choosed: ' + data.color_picker + '<br>';
                            var country = 'Seleted county is : ' + data.country + '<br>';

                            editor.insertContent(
                                username + colorpick + country
                            );
                        }
                    })
                    editor.insertContent(this.value());
                }
            })

        });
    });
})(jQuery);
