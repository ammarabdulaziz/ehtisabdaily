import PublicLayout from '@/layouts/public-layout';
import { Shield, User, Lock, Eye, FileText, Mail, Globe, AlertCircle } from 'lucide-react';

export default function PrivacyPolicy() {
    return (
        <PublicLayout title="Privacy Policy">
            <div className="py-16">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm shadow-2xl rounded-3xl overflow-hidden border border-slate-200/50 dark:border-slate-700/50">
                        <div className="px-8 py-12 sm:px-12">
                            {/* Header Section */}
                            <div className="text-center mb-16">
                                <div className="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl mb-6">
                                    <Shield className="h-8 w-8 text-white" />
                                </div>
                                <h1 className="text-5xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent mb-6">
                                    Privacy Policy
                                </h1>
                                <p className="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed">
                                    How we protect and handle your information with the highest standards of security and transparency
                                </p>
                            </div>
                            
                            {/* Last Updated Badge */}
                            <div className="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border border-emerald-200/50 dark:border-emerald-800/50 rounded-2xl p-6 mb-16">
                                <div className="flex items-center gap-3">
                                    <div className="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                        <AlertCircle className="h-4 w-4 text-white" />
                                    </div>
                                    <div>
                                        <p className="text-slate-700 dark:text-slate-300 font-medium">
                                            <span className="text-emerald-700 dark:text-emerald-300">Last updated:</span> {new Date().toLocaleDateString('en-US', { 
                                                year: 'numeric', 
                                                month: 'long', 
                                                day: 'numeric' 
                                            })}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div className="space-y-16">
                                {/* Section 1: Introduction */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center">
                                                <FileText className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">1. Introduction</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            Welcome to Ehtisab Daily ("we," "our," or "us"). This Privacy Policy explains how we collect, 
                                            use, disclose, and safeguard your information when you use our application. Please read this 
                                            privacy policy carefully. If you do not agree with the terms of this privacy policy, please 
                                            do not access the application.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 2: Information We Collect */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-green-500 to-blue-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-8">
                                            <div className="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-500 rounded-xl flex items-center justify-center">
                                                <Eye className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">2. Information We Collect</h2>
                                        </div>
                                        
                                        <div className="space-y-8">
                                            <div className="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
                                                <h3 className="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-3">
                                                    <User className="h-5 w-5 text-blue-500" />
                                                    2.1 Personal Information
                                                </h3>
                                                <p className="text-slate-700 dark:text-slate-300 mb-4 leading-relaxed">
                                                    We may collect personal information that you voluntarily provide to us when you:
                                                </p>
                                                <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Register for an account using Google authentication</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Use our financial tracking and asset management features</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Contact us for support</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Participate in surveys or feedback forms</span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div className="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
                                                <h3 className="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-3">
                                                    <Globe className="h-5 w-5 text-green-500" />
                                                    2.2 Google Authentication Data
                                                </h3>
                                                <p className="text-slate-700 dark:text-slate-300 mb-4 leading-relaxed">
                                                    When you sign in with Google, we collect the following information from your Google account:
                                                </p>
                                                <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Name and email address</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Profile picture (if you choose to share it)</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Google account ID</span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div className="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
                                                <h3 className="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-3">
                                                    <FileText className="h-5 w-5 text-purple-500" />
                                                    2.3 Financial Data
                                                </h3>
                                                <p className="text-slate-700 dark:text-slate-300 mb-4 leading-relaxed">
                                                    Our application is designed to help you track your financial assets, including:
                                                </p>
                                                <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Asset information (bank accounts, investments, etc.)</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Financial transactions and records</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Investment details and performance</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Borrowed and lent money records</span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div className="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
                                                <h3 className="text-xl font-semibold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-3">
                                                    <Eye className="h-5 w-5 text-indigo-500" />
                                                    2.4 Usage Information
                                                </h3>
                                                <p className="text-slate-700 dark:text-slate-300 mb-4 leading-relaxed">
                                                    We automatically collect certain information about your use of our application:
                                                </p>
                                                <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-indigo-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Device information (IP address, browser type, operating system)</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-indigo-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Usage patterns and preferences</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-indigo-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Application performance data</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-indigo-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Error logs and debugging information</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                {/* Section 3: How We Use Your Information */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-purple-500 to-pink-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                                <FileText className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">3. How We Use Your Information</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 mb-6 leading-relaxed">
                                            We use the information we collect to:
                                        </p>
                                        <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Provide and maintain our services</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Process transactions and manage your account</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Improve our application and develop new features</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Communicate with you about updates and support</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Ensure security and prevent fraud</span>
                                            </li>
                                        </ul>
                                    </div>
                                </section>

                                {/* Section 4: Data Security */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-green-500 to-emerald-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                                                <Lock className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">4. Data Security</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 mb-6 leading-relaxed">
                                            We implement appropriate security measures to protect your personal information:
                                        </p>
                                        <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Encryption of sensitive data in transit and at rest</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Secure authentication and access controls</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Regular security audits and updates</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Limited access to personal information on a need-to-know basis</span>
                                            </li>
                                        </ul>
                                    </div>
                                </section>

                                {/* Section 5: Data Sharing */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-orange-500 to-red-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center">
                                                <Shield className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">5. Data Sharing</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 mb-6 leading-relaxed">
                                            We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except:
                                        </p>
                                        <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>When required by law or legal process</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>To protect our rights and prevent fraud</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>With trusted service providers who assist in our operations</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>In connection with a business transfer or acquisition</span>
                                            </li>
                                        </ul>
                                    </div>
                                </section>

                                {/* Section 6: Your Rights */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center">
                                                <User className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">6. Your Rights</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 mb-6 leading-relaxed">
                                            You have the right to:
                                        </p>
                                        <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Access and review your personal information</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Correct inaccurate or incomplete data</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Delete your account and associated data</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Opt out of certain communications</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Export your data in a portable format</span>
                                            </li>
                                        </ul>
                                    </div>
                                </section>

                                {/* Section 7: Cookies and Tracking */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-emerald-500 to-teal-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center">
                                                <Eye className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">7. Cookies and Tracking</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            We use cookies and similar technologies to enhance your experience and analyze usage patterns. 
                                            You can control cookie settings through your browser preferences.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 8: Children's Privacy */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-pink-500 to-rose-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl flex items-center justify-center">
                                                <User className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">8. Children's Privacy</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            Our services are not intended for children under 13. We do not knowingly collect personal 
                                            information from children under 13. If we become aware of such collection, we will take 
                                            steps to delete the information.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 9: Changes to This Policy */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl flex items-center justify-center">
                                                <FileText className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">9. Changes to This Policy</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            We may update this Privacy Policy from time to time. We will notify you of any changes by 
                                            posting the new Privacy Policy on this page and updating the "Last updated" date.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 10: Contact Information */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-slate-500 to-slate-600 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-slate-500 to-slate-600 rounded-xl flex items-center justify-center">
                                                <Mail className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">10. Contact Information</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 mb-6 leading-relaxed">
                                            If you have any questions about this Privacy Policy, please contact us:
                                        </p>
                                        <div className="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700 p-8 rounded-2xl border border-slate-200 dark:border-slate-600">
                                            <div className="flex items-start gap-4">
                                                <div className="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                                    <Mail className="h-6 w-6 text-white" />
                                                </div>
                                                <div className="space-y-3">
                                                    <div>
                                                        <span className="font-semibold text-slate-900 dark:text-slate-100">Email:</span>
                                                        <span className="ml-2 text-slate-700 dark:text-slate-300">privacy@ehtisabdaily.com</span>
                                                    </div>
                                                    <div>
                                                        <span className="font-semibold text-slate-900 dark:text-slate-100">Website:</span>
                                                        <a href="http://ehtisabdaily.test" className="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                                            http://ehtisabdaily.test
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                {/* Footer */}
                                <div className="mt-16 pt-8 border-t border-slate-200 dark:border-slate-700">
                                    <div className="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
                                        <p className="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                            This privacy policy is effective as of the date listed above and will remain in effect except with respect to any changes in its provisions in the future, which will be in effect immediately after being posted on this page.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </PublicLayout>
    );
}