<div class="container-fluid text-center">
    <img src="{$IMAGES}/seward.jpg" class="img-fluid" alt="Seward, Alaska">
</div>
<div class="container contact">
    
    <h1 class="text-center">Contact Us</h1>

    <form action="{$SITE_URL}/submit-contact-form/">

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="form-group">
                    <label for="name">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea name="message" id="message" rows="10" class="form-control" required></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-right">
                <button class="btn btn-primary" type="submit">Send Message</button>
            </div>
        </div>

    </form>

    <div class="row">
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
            ç
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