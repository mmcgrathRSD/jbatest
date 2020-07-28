<?php
$identity = $this->auth->getIdentity();
?>
<form method="POST" enctype="multipart/form-data" action="<?php echo $item->generateStandardURL(); ?>/create/review">
<fieldset class="">
    <h4>How do you rate this product?</h4>
    <span id="input-message-box"></span>
    <table class="data-table" id="product-review-table">
    <colgroup><col>
    <col width="1">
    <col width="1">
    <col width="1">
    <col width="1">
    <col width="1">
    <col width="100%">
    </colgroup><tbody>
                            <tr>
                <th>Ease of Installation</th>
                                        <td class="value">
                <span class="rating-radio" data-id="EaseofInstallation_1" data-index="1"></span>
                <input type="radio" name="rating_criteria[ease_of_installation]" id="EaseofInstallation_1" value="1" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="EaseofInstallation_2" data-index="2"></span>
                <input type="radio" name="rating_criteria[ease_of_installation]" id="EaseofInstallation_2" value="2" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="EaseofInstallation_3" data-index="3"></span>
                <input type="radio" name="rating_criteria[ease_of_installation]" id="EaseofInstallation_3" value="3" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="EaseofInstallation_4" data-index="4"></span>
                <input type="radio" name="rating_criteria[ease_of_installation]" id="EaseofInstallation_4" value="4" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="EaseofInstallation_5" data-index="5"></span>
                <input type="radio" name="rating_criteria[ease_of_installation]" id="EaseofInstallation_5" value="5" class="radio">
                </td>
                                        <td class="selected-value" data-index="0">0 out of 5</td>
            </tr>
                            <tr>
                <th>Fit / Quality</th>
                                        <td class="value">
                <span class="rating-radio" data-id="FitQuality_1" data-index="1"></span>
                <input type="radio" name="rating_criteria[fit_and_quality]" id="FitQuality_1" value="1" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="FitQuality_2" data-index="2"></span>
                <input type="radio" name="rating_criteria[fit_and_quality]" id="FitQuality_2" value="2" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="FitQuality_3" data-index="3"></span>
                <input type="radio" name="rating_criteria[fit_and_quality]" id="FitQuality_3" value="3" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="FitQuality_4" data-index="4"></span>
                <input type="radio" name="rating_criteria[fit_and_quality]" id="FitQuality_4" value="4" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="FitQuality_5" data-index="5"></span>
                <input type="radio" name="rating_criteria[fit_and_quality]" id="FitQuality_5" value="5" class="radio">
                </td>
                                        <td class="selected-value" data-index="0">0 out of 5</td>
            </tr>
                            <tr>
                <th>Overall Satisfaction</th>
                                        <td class="value">
                <span class="rating-radio" data-id="OverallSatisfaction_1" data-index="1"></span>
                <input type="radio" name="rating_criteria[overall_satisfaction]" id="OverallSatisfaction_1" value="1" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="OverallSatisfaction_2" data-index="2"></span>
                <input type="radio" name="rating_criteria[overall_satisfaction]" id="OverallSatisfaction_2" value="2" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="OverallSatisfaction_3" data-index="3"></span>
                <input type="radio" name="rating_criteria[overall_satisfaction]" id="OverallSatisfaction_3" value="3" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="OverallSatisfaction_4" data-index="4"></span>
                <input type="radio" name="rating_criteria[overall_satisfaction]" id="OverallSatisfaction_4" value="4" class="radio">
                </td>
                                        <td class="value">
                <span class="rating-radio" data-id="OverallSatisfaction_5" data-index="5"></span>
                <input type="radio" name="rating_criteria[overall_satisfaction]" id="OverallSatisfaction_5" value="5" class="radio">
                </td>
                                        <td class="selected-value" data-index="0">0 out of 5</td>
            </tr>
                            </tbody>
    </table>
    <input type="hidden" name="validate_rating" class="validate-rating" value="">
                <ul class="form-list">
    <li>
            <label for="nickname_field" class="required"><em>*</em>Nickname</label>
            <div class="input-box">
                <input type="text" name="__screenname" id="nickname_field" class="input-text required-entry" value="<?php echo $identity->get('profile.screen_name'); ?>">
            </div>
    </li>
    <li>
            <label for="summary_field" class="summary required"><em>*</em>Summary of Your Review</label>
            <div class="input-box">
                <input type="text" name="title" id="summary_field" class="input-text required-entry" value="">
            </div>
    </li>
    <li>
            <label for="review_field" class="required"><em>*</em>Review</label>
            <div class="input-box">
                <textarea name="copy" id="review_field" cols="5" rows="3" class="required-entry"></textarea>
            </div>
    </li>
    <li>
        <div class="input-box button-review-indent">
            <button type="submit" title="Submit Review" class="button"><span><span>Submit Review</span></span></button>
        </div>
    </li>
    </ul>
</fieldset>
    <script>
    
$('#product-review-table td.value').hover(
function(){
var tr = $(this).parent();
tr.find('.rating-radio').removeClass('active');
tr.find('.rating-radio:lt('+$('.rating-radio', this).attr('data-index')+')').addClass('active');
},
function(){
var tr = $(this).parent();
tr.find('.rating-radio').removeClass('active');
tr.find('.rating-radio:lt('+tr.find('.selected-value').attr('data-index')+')').addClass('active');
}
);

$('#product-review-table .rating-radio').click(function(){
$('#' + $(this).attr('data-id')).attr('checked',true);
$(this).parent().parent().find('.selected-value')
.attr( 'data-index', $(this).attr('data-index') )
.text( Athlete.text.out_of.replace(/\%s/, $(this).attr('data-index')) );
});

</script>

</form>