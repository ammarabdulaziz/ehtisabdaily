import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

export default function TermsOfService() {
    return (
        <AppLayout>
            <Head title="Terms of Service" />
            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div className="px-6 py-8 sm:px-8">
                            <h1 className="text-3xl font-bold text-gray-900 mb-8">Terms of Service</h1>
                            
                            <div className="prose prose-lg max-w-none">
                                <p className="text-gray-600 mb-6">
                                    <strong>Last updated:</strong> {new Date().toLocaleDateString('en-US', { 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric' 
                                    })}
                                </p>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">1. Acceptance of Terms</h2>
                                    <p className="text-gray-700 mb-4">
                                        By accessing and using Ehtisab Daily ("the Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">2. Description of Service</h2>
                                    <p className="text-gray-700 mb-4">
                                        Ehtisab Daily is a personal financial tracking and asset management application that helps users:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Track and manage personal financial assets</li>
                                        <li>Monitor investments and financial performance</li>
                                        <li>Record borrowed and lent money transactions</li>
                                        <li>Access Islamic supplications and daily remembrances</li>
                                        <li>Maintain personal financial records securely</li>
                                    </ul>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">3. User Accounts and Authentication</h2>
                                    
                                    <h3 className="text-xl font-medium text-gray-900 mb-3">3.1 Account Creation</h3>
                                    <p className="text-gray-700 mb-4">
                                        To use our Service, you must create an account using Google authentication. By creating an account, you agree to:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Provide accurate and complete information</li>
                                        <li>Maintain the security of your account credentials</li>
                                        <li>Accept responsibility for all activities under your account</li>
                                        <li>Notify us immediately of any unauthorized use</li>
                                    </ul>

                                    <h3 className="text-xl font-medium text-gray-900 mb-3">3.2 Google Authentication</h3>
                                    <p className="text-gray-700 mb-4">
                                        Our Service uses Google OAuth for authentication. By signing in with Google, you:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Authorize us to access your basic Google account information</li>
                                        <li>Agree to Google's Terms of Service and Privacy Policy</li>
                                        <li>Understand that you can revoke access through your Google account settings</li>
                                    </ul>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">4. Acceptable Use</h2>
                                    
                                    <h3 className="text-xl font-medium text-gray-900 mb-3">4.1 Permitted Uses</h3>
                                    <p className="text-gray-700 mb-4">
                                        You may use our Service for:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Personal financial tracking and management</li>
                                        <li>Recording your own financial transactions</li>
                                        <li>Accessing Islamic supplications and remembrances</li>
                                        <li>Managing your personal assets and investments</li>
                                    </ul>

                                    <h3 className="text-xl font-medium text-gray-900 mb-3">4.2 Prohibited Uses</h3>
                                    <p className="text-gray-700 mb-4">
                                        You agree not to use the Service for:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Any unlawful purpose or to solicit others to perform unlawful acts</li>
                                        <li>Violating any international, federal, provincial, or state regulations, rules, laws, or local ordinances</li>
                                        <li>Transmitting or procuring the sending of any advertising or promotional material</li>
                                        <li>Attempting to gain unauthorized access to any portion of the Service</li>
                                        <li>Interfering with or disrupting the Service or servers connected to the Service</li>
                                        <li>Using the Service in any manner that could damage, disable, overburden, or impair the Service</li>
                                    </ul>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">5. Data and Privacy</h2>
                                    <p className="text-gray-700 mb-4">
                                        Your privacy is important to us. Our collection and use of personal information in connection with the Service is described in our Privacy Policy, which is incorporated into these Terms by reference.
                                    </p>
                                    <p className="text-gray-700 mb-4">
                                        By using the Service, you consent to the collection and use of information as described in our Privacy Policy.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">6. Intellectual Property Rights</h2>
                                    <p className="text-gray-700 mb-4">
                                        The Service and its original content, features, and functionality are and will remain the exclusive property of Ehtisab Daily and its licensors. The Service is protected by copyright, trademark, and other laws.
                                    </p>
                                    <p className="text-gray-700 mb-4">
                                        You retain ownership of any content you submit to the Service, but grant us a license to use, modify, and display such content in connection with providing the Service.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">7. Service Availability</h2>
                                    <p className="text-gray-700 mb-4">
                                        We strive to maintain the Service's availability, but we do not guarantee that the Service will be available at all times. We may:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Perform scheduled maintenance that may temporarily interrupt service</li>
                                        <li>Experience technical difficulties beyond our control</li>
                                        <li>Modify or discontinue the Service with reasonable notice</li>
                                    </ul>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">8. User Responsibilities</h2>
                                    <p className="text-gray-700 mb-4">
                                        As a user of the Service, you are responsible for:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Maintaining the confidentiality of your account information</li>
                                        <li>All activities that occur under your account</li>
                                        <li>Ensuring the accuracy of financial data you input</li>
                                        <li>Complying with all applicable laws and regulations</li>
                                        <li>Using the Service in accordance with these Terms</li>
                                    </ul>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">9. Disclaimers and Limitations</h2>
                                    
                                    <h3 className="text-xl font-medium text-gray-900 mb-3">9.1 Service Disclaimer</h3>
                                    <p className="text-gray-700 mb-4">
                                        The Service is provided "as is" and "as available" without warranties of any kind, either express or implied, including but not limited to warranties of merchantability, fitness for a particular purpose, or non-infringement.
                                    </p>

                                    <h3 className="text-xl font-medium text-gray-900 mb-3">9.2 Financial Advice Disclaimer</h3>
                                    <p className="text-gray-700 mb-4">
                                        The Service is for personal financial tracking purposes only and does not constitute financial, investment, or legal advice. You should consult with qualified professionals for financial decisions.
                                    </p>

                                    <h3 className="text-xl font-medium text-gray-900 mb-3">9.3 Limitation of Liability</h3>
                                    <p className="text-gray-700 mb-4">
                                        In no event shall Ehtisab Daily be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your use of the Service.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">10. Termination</h2>
                                    <p className="text-gray-700 mb-4">
                                        We may terminate or suspend your account and bar access to the Service immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever, including without limitation if you breach the Terms.
                                    </p>
                                    <p className="text-gray-700 mb-4">
                                        You may terminate your account at any time by contacting us or through your account settings. Upon termination, your right to use the Service will cease immediately.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">11. Governing Law</h2>
                                    <p className="text-gray-700 mb-4">
                                        These Terms shall be interpreted and governed by the laws of the jurisdiction in which Ehtisab Daily operates, without regard to its conflict of law provisions.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">12. Changes to Terms</h2>
                                    <p className="text-gray-700 mb-4">
                                        We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days notice prior to any new terms taking effect.
                                    </p>
                                    <p className="text-gray-700 mb-4">
                                        By continuing to access or use our Service after those revisions become effective, you agree to be bound by the revised terms.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">13. Severability</h2>
                                    <p className="text-gray-700 mb-4">
                                        If any provision of these Terms is held to be invalid or unenforceable by a court, the remaining provisions of these Terms will remain in effect.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">14. Contact Information</h2>
                                    <p className="text-gray-700 mb-4">
                                        If you have any questions about these Terms of Service, please contact us:
                                    </p>
                                    <div className="bg-gray-50 p-4 rounded-lg">
                                        <p className="text-gray-700">
                                            <strong>Email:</strong> legal@ehtisabdaily.com<br />
                                            <strong>Website:</strong> <a href="http://ehtisabdaily.test" className="text-blue-600 hover:text-blue-800">http://ehtisabdaily.test</a>
                                        </p>
                                    </div>
                                </section>

                                <div className="mt-8 pt-6 border-t border-gray-200">
                                    <p className="text-sm text-gray-500">
                                        These terms of service are effective as of the date listed above and will remain in effect except with respect to any changes in their provisions in the future, which will be in effect immediately after being posted on this page.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
