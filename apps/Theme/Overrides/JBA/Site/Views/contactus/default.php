<div class="main row clearfix">
    <div class="col-main grid_18">     	                    
        <tmpl type="modules" name="contact-us" />

        <form method="post" action="/contact-us">
            <div class="fieldset">
                <h2 class="legend theme_font">Contact Information</h2>
                <ul class="form-list">
                    <li class="fields">
                        <div class="field">
                            <label for="name" class="required"><em>*</em>Name</label>
                            <div class="input-box">
                                <input name="z_name" id="name" title="Name" value=""
                                    class="input-text required-entry" type="text" required>
                            </div>
                        </div>
                        <div class="field">
                            <label for="email" class="required"><em>*</em>Email</label>
                            <div class="input-box">
                                <input name="z_requester" id="email" title="Email" value=""
                                    class="input-text required-entry validate-email" type="text" required>
                            </div>
                        </div>
                    </li>
                    <div class="input-box">
                        <label for="order">Order Number</label><br>
                        <input name="z_order" id="order" title="Order Number" value="" class="input-text"
                            type="text">
                    </div>
                    <li><br>
                        <label for="telephone">Telephone</label>
                        <div class="input-box">
                            <input name="z_telephone" id="telephone" title="Telephone" value="" class="input-text"
                                type="text">
                        </div>
                    </li>
                    <li>
                        <div class="input-box">
                            <label for="subject">Subject</label><br>
                            <input name="z_subject" id="subject" title="Subject" value="" class="input-text"
                                type="text">
                        </div><br>

                        <label for="type" class="required"><em>*</em>Type</label>
                        <select class="required-entry" name="z_drop-down" required>
                            <option value="">Please select question type</option>
                            <option value="Tracking">Where can I track my order? Has my order shipped?</option>
                            <option value="Inventory">Do you have this product in stock? How long would it take to receive?</option>
                            <option value="Damaged">My parts were damaged during shipment!</option>
                            <option value="Warranty">My parts aren't working correctly or have stopped working!</option>
                            <option value="Technical">I have pre-sale questions about certain products.</option>
                            <option value="Install">I'm not sure how to install the part I just purchased from you...</option>
                            <option value="RMA">I'd like to cancel my order or I'd like to return items from my order.</option>
                            <option value="Other">I've got questions that don't pertain to products on your website.</option>
                        </select>
                    </li>
                    <li class="wide">
                        <br>
                        <label for="comment" class="required"><em>*</em>Comment</label>
                        <div class="input-box">
                            <textarea name="z_description" id="comment" title="Comment"
                                class="required-entry input-text" cols="5" rows="3" required></textarea>
                        </div>
                    </li>
                </ul>
            </div>
            <input class="mhhs-input" type="text" name="url" autocomplete="off" autofill="off" style="display: none;">
            <div class="buttons-set">
                <p class="required">* Required Fields</p>
                <button type="submit" title="Submit" class="button"><span><span>Submit</span></span></button>
            </div>
        </form>

        <div class="m20-top"></div>
    </div>
</div>