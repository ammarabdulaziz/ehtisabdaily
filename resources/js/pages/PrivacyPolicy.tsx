import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';

export default function PrivacyPolicy() {
    return (
        <AppLayout>
            <Head title="Privacy Policy" />
            <div className="min-h-screen bg-gray-50 py-12">
                <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div className="px-6 py-8 sm:px-8">
                            <h1 className="text-3xl font-bold text-gray-900 mb-8">Privacy Policy</h1>
                            
                            <div className="prose prose-lg max-w-none">
                                <p className="text-gray-600 mb-6">
                                    <strong>Last updated:</strong> {new Date().toLocaleDateString('en-US', { 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric' 
                                    })}
                                </p>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">1. Introduction</h2>
                                    <p className="text-gray-700 mb-4">
                                        Welcome to Ehtisab Daily ("we," "our," or "us"). This Privacy Policy explains how we collect, 
                                        use, disclose, and safeguard your information when you use our application. Please read this 
                                        privacy policy carefully. If you do not agree with the terms of this privacy policy, please 
                                        do not access the application.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">2. Information We Collect</h2>
                                    
                                    <h3 className="text-xl font-medium text-gray-900 mb-3">2.1 Personal Information</h3>
                                    <p className="text-gray-700 mb-4">
                                        We may collect personal information that you voluntarily provide to us when you:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Register for an account using Google authentication</li>
                                        <li>Use our financial tracking and asset management features</li>
                                        <li>Contact us for support</li>
                                        <li>Participate in surveys or feedback forms</li>
                                    </ul>

                                    <h3 className="text-xl font-medium text-gray-900 mb-3">2.2 Google Authentication Data</h3>
                                    <p className="text-gray-700 mb-4">
                                        When you sign in with Google, we collect the following information from your Google account:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Name and email address</li>
                                        <li>Profile picture (if you choose to share it)</li>
                                        <li>Google account ID</li>
                                    </ul>

                                    <h3 className="text-xl font-medium text-gray-900 mb-3">2.3 Financial Data</h3>
                                    <p className="text-gray-700 mb-4">
                                        Our application is designed to help you track your financial assets, including:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Asset information (bank accounts, investments, etc.)</li>
                                        <li>Financial transactions and records</li>
                                        <li>Investment details and performance</li>
                                        <li>Borrowed and lent money records</li>
                                    </ul>

                                    <h3 className="text-xl font-medium text-gray-900 mb-3">2.4 Usage Information</h3>
                                    <p className="text-gray-700 mb-4">
                                        We automatically collect certain information about your use of our application:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Device information (IP address, browser type, operating system)</li>
                                        <li>Usage patterns and preferences</li>
                                        <li>Application performance data</li>
                                        <li>Error logs and debugging information</li>
                                    </ul>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">3. How We Use Your Information</h2>
                                    <p className="text-gray-700 mb-4">
                                        We use the information we collect for the following purposes:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>To provide and maintain our financial tracking services</li>
                                        <li>To authenticate your identity using Google OAuth</li>
                                        <li>To personalize your experience and provide relevant features</li>
                                        <li>To process transactions and maintain your financial records</li>
                                        <li>To communicate with you about your account and our services</li>
                                        <li>To improve our application and develop new features</li>
                                        <li>To ensure security and prevent fraud</li>
                                        <li>To comply with legal obligations</li>
                                    </ul>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">4. Information Sharing and Disclosure</h2>
                                    <p className="text-gray-700 mb-4">
                                        We do not sell, trade, or otherwise transfer your personal information to third parties, except in the following circumstances:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li><strong>With your consent:</strong> We may share your information when you explicitly consent</li>
                                        <li><strong>Service providers:</strong> We may share information with trusted third-party service providers who assist us in operating our application</li>
                                        <li><strong>Legal requirements:</strong> We may disclose information when required by law or to protect our rights</li>
                                        <li><strong>Business transfers:</strong> In the event of a merger or acquisition, your information may be transferred</li>
                                    </ul>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">5. Data Security</h2>
                                    <p className="text-gray-700 mb-4">
                                        We implement appropriate technical and organizational security measures to protect your personal information:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Encryption of data in transit and at rest</li>
                                        <li>Secure authentication using Google OAuth</li>
                                        <li>Regular security audits and updates</li>
                                        <li>Access controls and user authentication</li>
                                        <li>Secure data storage and backup procedures</li>
                                    </ul>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">6. Your Rights and Choices</h2>
                                    <p className="text-gray-700 mb-4">
                                        You have the following rights regarding your personal information:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li><strong>Access:</strong> You can request access to your personal information</li>
                                        <li><strong>Correction:</strong> You can request correction of inaccurate information</li>
                                        <li><strong>Deletion:</strong> You can request deletion of your personal information</li>
                                        <li><strong>Portability:</strong> You can request a copy of your data in a portable format</li>
                                        <li><strong>Withdrawal of consent:</strong> You can withdraw consent for data processing</li>
                                    </ul>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">7. Google Authentication</h2>
                                    <p className="text-gray-700 mb-4">
                                        Our application uses Google OAuth for authentication. By signing in with Google, you agree to:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>Google's Terms of Service and Privacy Policy</li>
                                        <li>Our collection and use of your Google account information as described in this policy</li>
                                        <li>Our use of Google's authentication services</li>
                                    </ul>
                                    <p className="text-gray-700 mb-4">
                                        You can revoke our access to your Google account at any time through your Google account settings.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">8. Data Retention</h2>
                                    <p className="text-gray-700 mb-4">
                                        We retain your personal information for as long as necessary to provide our services and fulfill the purposes outlined in this privacy policy. We will delete your information when:
                                    </p>
                                    <ul className="list-disc list-inside text-gray-700 mb-4 space-y-2">
                                        <li>You request deletion of your account</li>
                                        <li>Your account has been inactive for an extended period</li>
                                        <li>We are no longer legally required to retain the information</li>
                                    </ul>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">9. International Data Transfers</h2>
                                    <p className="text-gray-700 mb-4">
                                        Your information may be transferred to and processed in countries other than your own. We ensure that such transfers comply with applicable data protection laws and implement appropriate safeguards.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">10. Children's Privacy</h2>
                                    <p className="text-gray-700 mb-4">
                                        Our application is not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you are a parent or guardian and believe your child has provided us with personal information, please contact us.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">11. Changes to This Privacy Policy</h2>
                                    <p className="text-gray-700 mb-4">
                                        We may update this privacy policy from time to time. We will notify you of any changes by posting the new privacy policy on this page and updating the "Last updated" date. You are advised to review this privacy policy periodically for any changes.
                                    </p>
                                </section>

                                <section className="mb-8">
                                    <h2 className="text-2xl font-semibold text-gray-900 mb-4">12. Contact Information</h2>
                                    <p className="text-gray-700 mb-4">
                                        If you have any questions about this privacy policy or our data practices, please contact us:
                                    </p>
                                    <div className="bg-gray-50 p-4 rounded-lg">
                                        <p className="text-gray-700">
                                            <strong>Email:</strong> privacy@ehtisabdaily.com<br />
                                            <strong>Website:</strong> <a href="http://ehtisabdaily.test" className="text-blue-600 hover:text-blue-800">http://ehtisabdaily.test</a>
                                        </p>
                                    </div>
                                </section>

                                <div className="mt-8 pt-6 border-t border-gray-200">
                                    <p className="text-sm text-gray-500">
                                        This privacy policy is effective as of the date listed above and will remain in effect except with respect to any changes in its provisions in the future, which will be in effect immediately after being posted on this page.
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
