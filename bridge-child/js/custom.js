(function ($) {
    $(document).ready(function(){
        ennergramActionClick();
        backtoDefault();
    });

    /**
     * Action Click on ennergram
     */
    function ennergramActionClick(){
        //Get list client
        $("#ennergram_button .qode_clients").find(".qode_client_holder").each(function(){
            $(this).on("click", function(e){
                e.preventDefault();
                //Order number of item on list client
                var index = $(this).index();
                //Increase index 1
                index = parseInt(index) + 1;
                $("#ennergram_slider .flex-control-nav li:nth-of-type("+index+") a").click();
            })
        });
    }
    /**
     * Action Click on "back" to show the default
     */
    function backtoDefault() {
        $("#ennergram_slider .button_back_to_default").on("click", function(e){
            e.preventDefault();
            $("#ennergram_slider .flex-control-nav li:nth-of-type(1) a").click();
        });
    }

})(jQuery);
