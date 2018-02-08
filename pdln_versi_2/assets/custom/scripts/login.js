var Login = function() {
    /**
     * Description
     * @method handleLogin
     * @return 
     */
    var handleLogin = function() {
        var form = $('.login-form'),
            error = $('.alert-danger', form);
            warning = $('.alert-warning', form);
        var validator = form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            rules: {
                identity: {
                    required: true
                },
                password: {
                    required: true
                },
				userCaptcha:{
					required: true
				} 
            },

            messages: {
                identity: {
                    required: "Username tidak boleh kosong"
                },
                password: {
                    required: "Password tidak boleh kosong."
                },
                userCaptcha: {
                    required: "Kode keamanan tidak boleh kosong."
                }
            },
            /**
             * Description
             * @method invalidHandler
             * @param {} event
             * @param {} validator
             * @return 
             */
            invalidHandler: function(event, validator) { //display error alert on form submit   
                error.fadeTo(3000, 500).slideUp(500, function(){});
            },

            /**
             * Description
             * @method highlight
             * @param {} element
             * @return 
             */
            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // remove error class to the control group
            },
            /**
             * Description
             * @method success
             * @param {} label
             * @return 
             */
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            /**
             * Description
             * @method errorPlacement
             * @param {} error
             * @param {} element
             * @return 
             */
            errorPlacement: function(error, element) {
                error.insertAfter(element.closest('.input-icon'));
            },
            /**
             * Description
             * @method submitHandler
             * @param {} form
             * @return 
             */
            submitHandler: function(){
                App.blockUI({ 
                                boxed: true,
                                message: 'Sedang di proses....'
                            });
                $.ajax({
                    type : 'POST',
                    dataType : 'JSON',
                    data : form.serialize(),
                    url  : BASE_URL+'auth/login',
                    success : function(msg) {                        
                        App.unblockUI();
                        

                        
                            if(msg.success === true){    
                                if($('#redirect').val() != ''){
                                    window.location.href = BASE_URL+'/'+$('#redirect').val();   
                                }else{     
                                    window.location.href = BASE_URL;  
                                }                          
                            }else{
    							//grecaptcha.reset(); 
                                warning.fadeTo(3000, 500).slideUp(500, function(){})
                                    .html(msg.msg);
                                App.scrollTo(warning, -200);
                                return false; 
                            }
                        // }
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        App.unblockUI();                      
                    }
                });
                return false;
            }
        });

        $('.login-form input').keypress(function(e) {
            if (e.which == 13) {
                if (validator) {
                    $('.login-form').submit();
                }
                return false;
            }
        });

        $('#back-btn').click(function(){
            $('.login-form')[0].reset();
            $('.login-form').show();
            $('.forget-form').hide();
            validator.resetForm();
        });
    };

    
    /**
     * Description
     * @method handleForgotPassword
     * @return 
     */
    var handleForgotPasword= function() {
        var form = $('.forget-form'),
            error = $('.alert-danger', form),
            warning = $('.alert-warning', form),
            info = $('.alert-info', form);
        var validator = form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            // ignore: "",
            rules: {
                email_forgot: { 
                    required: true,
                    email: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                email_forgot: {
                    required: "Email tidak boleh kosong."
                }
            },

            /**
             * Description
             * @method invalidHandler
             * @param {} event
             * @param {} validator
             * @return 
             */
            invalidHandler: function(event, validator) { //display error alert on form submit   
                error.fadeTo(3000, 500).slideUp(500, function(){}).html("Masukkan Email registrasi anda.");               
            },

            /**
             * Description
             * @method highlight
             * @param {} element
             * @return 
             */
            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // remove error class to the control group
            },

            /**
             * Description
             * @method success
             * @param {} label
             * @return 
             */
            success: function(label) {
                label.closest('.form-group').removeClass('has-error');
                label.remove();
            },

            /**
             * Description
             * @method errorPlacement
             * @param {} error
             * @param {} element
             * @return 
             */
            errorPlacement: function(error, element) {
                error.insertAfter(element.closest('.input-icon'));
            },

            /**
             * Description
             * @method submitHandler
             * @param {} form
             * @return 
             */
            submitHandler: function(form) {
                form = $('.forget-form');
                App.blockUI({
                                boxed: true,
                                message: 'Sedang di proses....'
                            });
                $.ajax({
                    type : 'POST',
                    dataType : 'JSON',
                    data : form.serialize(),
                    url  : BASE_URL+'auth/forgot_password',
                    success : function(msg) {
                        App.unblockUI();
                        if(msg.success === true){
                            bootbox.alert({
                                message : "msg.info",
                                title   : '<span class="font-green bold"> <strong> <i class="fa fa-check"> </i> Sukses </strong><span>'
                            });
                        }else{
                            warning.fadeTo(3000, 500).slideUp(500, function(){})
                                .html(msg.msg);
                            App.scrollTo(warning, -200);
                            return false;
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown){
                        App.unblockUI();
                        bootbox.alert({
                            message : '<span class="font-yellow"> Mohon maaf koneksi bermasalah.</span> <br />'+
                                      ' Silahkan coba beberapa saat lagi <strong>atau</strong> hubungi <strong> Administrator SIKTLN. </strong>',
                            title   : '<span class="font-red bold"> <strong> <i class="fa fa-warning"> </i> Error!! </strong><span>'                            
                        });                    
                    }
                });
                return false;
                
            }
        });

        $('.forget-password input').keypress(function(e) {
            if (e.which == 13) {
                if (validator) {
                    $('.forget-password').submit();
                }
                return false;
            }
        });
        $('#forget-password').click(function() {
            $('.form-group').removeClass('has-error');
            $('.login-form').hide();
            $('.forget-form')[0].reset();
            $('.forget-form').show();
            validator.resetForm();
        });
        
    };
   
    return {
        //main function to initiate the module
        /**
         * Description
         * @method init
         * @return 
         */ 
        init: function() {

            handleLogin();
            handleForgotPasword();

            $('.forget-form').hide();
        }

    };

}();
jQuery(document).ready(function() {
    Login.init();
});