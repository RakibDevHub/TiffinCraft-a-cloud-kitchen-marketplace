<?php
$pageTitle = "Contact Us - TiffinCraft";

$helper = new App\Utils\Helper();
if (empty($_SESSION['csrf_token'])) {
    $csrfToken = $helper->generateCsrfToken();
}

ob_start();
?>

<section class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-12" data-aos="zoom-in">
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">Get in Touch</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Have questions or feedback? We'd love to hear from you!
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8" data-aos="zoom-in" data-aos-delay="200">
            <!-- Contact Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6">
                        <h2 class="text-xl font-bold text-white">Contact Information</h2>
                    </div>
                    <div class="p-6 sm:p-8 space-y-4">
                        <div class="flex items-start">
                            <div class="bg-orange-100 p-3 rounded-full text-orange-600 mr-4">
                                <i class="fas fa-map-marker-alt text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Our Location</h3>
                                <p class="text-gray-600">Bashundhara R/A, Block B, Dhaka 1212</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-orange-100 p-3 rounded-full text-orange-600 mr-4">
                                <i class="fas fa-phone-alt text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Phone Number</h3>
                                <p class="text-gray-600">+880 1XXX-XXXXXX</p>
                                <p class="text-gray-600">+880 1XXX-XXXXXX</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="bg-orange-100 p-3 rounded-full text-orange-600 mr-4">
                                <i class="fas fa-envelope text-lg"></i>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900">Email Address</h3>
                                <p class="text-gray-600">support@tiffincraft.com</p>
                                <p class="text-gray-600">info@tiffincraft.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6">
                        <h2 class="text-xl font-bold text-white">Business Hours</h2>
                    </div>
                    <div class="p-6 sm:p-8">
                        <ul class="space-y-3">
                            <li class="flex justify-between">
                                <span class="text-gray-700">Monday - Friday</span>
                                <span class="font-medium">9:00 AM - 8:00 PM</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-700">Saturday</span>
                                <span class="font-medium">10:00 AM - 6:00 PM</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-700">Sunday</span>
                                <span class="font-medium text-orange-600">Closed</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden" data-aos="zoom-in" data-aos-delay="300">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6">
                    <h2 class="text-xl font-bold text-white">Send us a message</h2>
                </div>
                <div class="px-6 sm:px-8">
                    <?php if (isset($_SESSION['contact_success'])): ?>
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?= htmlspecialchars($_SESSION['contact_success']) ?>
                            <?php unset($_SESSION['contact_success']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['contact_error'])): ?>
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?= htmlspecialchars($_SESSION['contact_error']) ?>
                            <?php unset($_SESSION['contact_error']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="/contact/submit" method="POST" class="space-y-5">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" id="name" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus"
                                placeholder="Your name">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email
                                Address</label>
                            <input type="email" name="email" id="email" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus"
                                placeholder="your@email.com">
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <select name="subject" id="subject" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus">
                                <option value="" disabled selected>Select a subject</option>
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Order Issues">Order Issues</option>
                                <option value="Business Partnership">Business Partnership</option>
                                <option value="Feedback">Feedback</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea name="message" id="message" rows="4" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus"
                                placeholder="Your message here..."></textarea>
                        </div>

                        <div>
                            <button type="submit"
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                                Send Message <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include BASE_PATH . '/src/views/index.php';
?>