<div class="container">
   
    <h1 class="text-center">New Resident Application</h1>
    <form action="{$SITE_URL}/save_application" method="post">
        
        <div class="row">    
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="name">Birthdate</label>
                    <input type="text" class="form-control datepicker" id="birthdate" name="birthdate" required>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="name">Gender</label>
                    <select name="gender" class="form-control" id="gender">
                        <option value=""></option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
            </div>
        </div>
            
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="care-needs">Care Needs</label>
                    <select name="care_needs[]" id="care-needs" multiple required>
                        <option value=""></option>
                        {foreach from=$care_needs item="need"}
                        <option value="{$need->id}">{$need->name}</option>
                        {/foreach}
                    </select>
                </div>
            </div>

            <div class="col-sm-6 mt-4">
                <div class="form-check">
                    <input type="checkbox" id="diabetes" name="diabetes" value="1" class="form-check-input">
                    <label for="diabetes" class="form-check-label">Does the resident have diabetes?</label>
                </div>
                <div class="form-check" id="self-manage">
                    <input type="checkbox" id="self-manage-diabetes" name="self_manage_diabetes" value="1" class="form-check-input">
                    <label for="diabetes" class="form-check-label">Can the resident manage their own diabetes?</label>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="dementia">Does the resident have any cognitive impairment or dementia?</label>
                    <select name="dementia" id="dementia" class="form-control" required>
                        <option value=""></option>
                        {foreach from=$dementia item="d"}
                        <option value="{$d->id}">{$d->level}</option>
                        {/foreach}
                    </select>
                </div>
                <div class="form-check">
                    <input type="checkbox" id="wander-risk" name="wander_risk" value="1" class="form-check-input">
                    <label for="wander-risk" class="form-check-label">Does the resident have any tendencies to wander?</label>
                </div>
            </div>
        </div>


        <!-- mental health diagnosis -->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-check">
                    <input type="checkbox" id="mh-diagnosis" name="mh_diagnosis" value="1" class="form-check-input" required>
                    <label for="mh-diagnosis" class="form-check-label">Has the resident ever had a mental health diagnosis?</label>
                </div>
            </div>
        </div>
        <div id="mh-diagnosis-row" class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="mh-explanation">What is the diagnosis and is it controlled by medication?</label>
                    <input type="text" class="form-control" id="mh-explanation" name="mh_explanation">
                </div>
            </div>
        </div>
        <!-- /mental health diagnosis -->

        <!-- chemical dependencies -->
        <div class="row">
            <div class="col-sm-12">
                <div class="form-check">
                    <input type="checkbox" id="chemical-dependencies" name="chemical_dependencies" value="" class="form-check-input">
                    <label for="chemical-dependencies" class="form-check-label" required>Does the resident have any chemical dependencies?</label>
                </div>
            </div>
        </div>
        <div id="dependency-explanation-row" class="row">
            <div class="col-sm-12"> 
                <div class="form-group">
                    <label for="dependency-explanation">Explain the chemical dependency</label>
                    <input type="text" class="form-control" id="dependency-explanation" name="dependency_explanation">
                </div>
            </div>
        </div>
        <!-- /chemical dependencies -->

        <!-- pcp info -->
        <div class="row">
            <div class="col-sm-7">
                <div class="form-group">
                    <label for="pcp">Primary Care Physician</label>
                    <input type="text" class="form-control" id="pcp" name="pcp">
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <label for="pcp-phone">Primary Care Physician Phone</label>
                    <input type="text" class="form-control" id="pcp-phone" name="pcp_phone">
                </div>
            </div>
        </div>
        <!-- /pcp info -->

        <div class="row">
            <div class="col-sm-12 text-right">
                <button class="btn btn-primary" type="submit">Send</button>
            </div>
        </div>

    </form>

    <div class="row mt-5">
        <div class="col-lg-7">
            <h3>General Inquiries</h3>
             <p><a href="tel:907.868.2688" class="text-warning"><i class="fas fa-phone"></i>&nbsp;&nbsp;907.868.2688</a></p>
             <p><a href="mailto:info@aspencreekalaska.com" class="text-warning"><i class="fas fa-envelope"></i></i>&nbsp;&nbsp;info@aspencreekalaska.com</a></p>
             <p><i class="fas fa-map-marked-alt"></i><span class="ml-1">&nbsp;5915 Petersburg Street</span><br><span class="ml-4">&nbsp;Anchorage, Alaska 99507</span>
        </div>
        <div class="lg-5">
            <h3>Office Hours</h3>
            <div class="row">
                <div class="col-2 pt-2">
                    <i class="far fa-clock fa-2x"></i>
                </div>
                <div class="col-10">
                    <p class="ml-4">Monday – Friday<br>9:00 am – 5:00 pm</p>
                </div>
            </div>
            
        </div>
    </div>
</div>


<!-- map -->
<div class="container-fluid mt-5">
    <div class="embed-responsive embed-responsive-21by9">
         <iframe class="embed_responsive-item" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d36623.229586051166!2d-149.81794623140055!3d61.15637104241877!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x56c897743c584f2b%3A0xa639396185109446!2s5915%20Petersburg%20St%2C%20Anchorage%2C%20AK%2099507%2C%20USA!5e0!3m2!1sen!2sin!4v1576504296729!5m2!1sen!2sin"></iframe> 
    </div>
</div>
<!-- /map -->