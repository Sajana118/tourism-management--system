<?php
error_reporting(0);

$contactSuccess = '';
$contactError = '';

if(isset($_POST['submit_contact'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    
    $sql = "INSERT INTO tblenquiry(FullName, EmailId, MobileNumber, Subject, Description) 
            VALUES(:name, :email, :mobile, :subject, :message)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->bindParam(':subject', $subject, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);
    $query->execute();
    
    if($dbh->lastInsertId()) {
        $contactSuccess = "Thank you! Your message has been sent successfully. We'll get back to you soon.";
    } else {
        $contactError = "Something went wrong. Please try again.";
    }
}
?>

<!-- Contact Us Modal - Bootstrap 5 -->
<div class="modal fade" id="writeusModal" tabindex="-1" aria-labelledby="writeusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #003893, #2563eb); color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="writeusModalLabel">
                    <i class="fas fa-envelope me-2"></i>Contact Us
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 40px;">
                <?php if($contactError) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo $contactError; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                <?php if($contactSuccess) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo $contactSuccess; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php } ?>
                
                <div class="row mb-4">
                    <div class="col-md-4 text-center mb-3">
                        <div style="background: #f8fafc; padding: 20px; border-radius: 10px;">
                            <i class="fas fa-map-marker-alt fa-2x mb-2" style="color: #DC143C;"></i>
                            <h6 style="font-weight: 600;">Visit Us</h6>
                            <p style="font-size: 0.9rem; color: #64748b; margin: 0;">Kathmandu, Nepal</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <div style="background: #f8fafc; padding: 20px; border-radius: 10px;">
                            <i class="fas fa-phone-alt fa-2x mb-2" style="color: #DC143C;"></i>
                            <h6 style="font-weight: 600;">Call Us</h6>
                            <p style="font-size: 0.9rem; color: #64748b; margin: 0;">+977-1-4567890</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <div style="background: #f8fafc; padding: 20px; border-radius: 10px;">
                            <i class="fas fa-envelope fa-2x mb-2" style="color: #DC143C;"></i>
                            <h6 style="font-weight: 600;">Email Us</h6>
                            <p style="font-size: 0.9rem; color: #64748b; margin: 0;">info@nepaltourism.com</p>
                        </div>
                    </div>
                </div>
                
                <form method="post" name="contact">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact-name" class="form-label" style="font-weight: 600; color: #003893;">
                                <i class="fas fa-user me-2"></i>Full Name *
                            </label>
                            <input type="text" class="form-control" name="name" id="contact-name" placeholder="Your full name" required style="padding: 12px; border-radius: 8px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact-email" class="form-label" style="font-weight: 600; color: #003893;">
                                <i class="fas fa-envelope me-2"></i>Email Address *
                            </label>
                            <input type="email" class="form-control" name="email" id="contact-email" placeholder="your@email.com" required style="padding: 12px; border-radius: 8px;">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="contact-mobile" class="form-label" style="font-weight: 600; color: #003893;">
                                <i class="fas fa-mobile-alt me-2"></i>Mobile Number *
                            </label>
                            <input type="text" class="form-control" name="mobile" id="contact-mobile" placeholder="10 digit mobile" maxlength="10" required style="padding: 12px; border-radius: 8px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contact-subject" class="form-label" style="font-weight: 600; color: #003893;">
                                <i class="fas fa-tag me-2"></i>Subject *
                            </label>
                            <select class="form-select" name="subject" id="contact-subject" required style="padding: 12px; border-radius: 8px;">
                                <option value="">Select Subject</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Booking Question">Booking Question</option>
                                <option value="Package Information">Package Information</option>
                                <option value="Payment Issue">Payment Issue</option>
                                <option value="Cancellation">Cancellation</option>
                                <option value="Feedback">Feedback</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="contact-message" class="form-label" style="font-weight: 600; color: #003893;">
                            <i class="fas fa-comment me-2"></i>Your Message *
                        </label>
                        <textarea class="form-control" name="message" id="contact-message" rows="4" placeholder="Tell us how we can help you..." required style="padding: 12px; border-radius: 8px;"></textarea>
                    </div>
                    
                    <button type="submit" name="submit_contact" class="btn w-100" style="background: #DC143C; color: white; padding: 12px; border-radius: 8px; font-weight: 600; border: none;">
                        <i class="fas fa-paper-plane me-2"></i>Send Message
                    </button>
                </form>
                
                <hr class="my-4">
                <p class="text-center text-muted" style="font-size: 0.85rem; margin: 0;">
                    <i class="fas fa-clock me-1"></i>We typically respond within 24 hours
                </p>
            </div>
        </div>
    </div>
</div>
