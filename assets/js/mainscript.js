$(document).ready(function() {

    $( 'input[type=radio]' ).click(function() {
        $( 'div#error' ).hide();
        $( 'input[name="' + this.name + '"]' ).each(function(){
            $(this).parent().parent().toggleClass('checkedlabel', this.checked);
        });
    });
    $( '#frmcrackit' ).submit(function(e) {
        e.preventDefault();
        if (!$( 'input[type=radio]' ).is( ':checked' )) {
            $( 'div#error' ).show();
            $( '#error' ).html( 'Please select any of the option below' );
            $( '#error')[0].scrollIntoView();
        }
        else {
            var selValue = $( 'input[type="radio"]:checked' ).val();
            selValue = selValue.replace(/\d+/g, '');
            var form = $(this);
            $.ajax({
                type: 'POST',
                url: 'ajaxprocess.php',
                data: form.serialize(),
                beforeSend: function () {
                    $( '#loader' ).removeClass( 'hidden' )
                },
                success: function(result) {
                    var jsonData = JSON.parse(result);
                    if (jsonData.length > 0) {
                        var idsresponse = '';
                        for (var i = 0; i < jsonData.length; i++) {
                            idsresponse += jsonData[i] + '<br>';
                        }
                    }
                    else {
                        idsresponse = 'No Data';
                    }
                    $('.modal-title').html("User IDs - " + selValue + " passwords");
                    $('.modal-body').html(idsresponse);
                    $('#userModal').modal('show');
                },
                complete: function () {
                    $('#loader').addClass('hidden')
                },
                error: function(){
                    $("#error").html('There was some problem while loading the results, please try again.');
                }
            });
        }
    });
});