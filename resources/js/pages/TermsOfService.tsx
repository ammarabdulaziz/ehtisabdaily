import PublicLayout from '@/layouts/public-layout';
import { Scale, FileText, User, Shield, AlertTriangle, Lock, Globe, Mail, AlertCircle, BookOpen, Users, Settings } from 'lucide-react';

export default function TermsOfService() {
    return (
        <PublicLayout title="Terms of Service">
            <div className="py-16">
                <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm shadow-2xl rounded-3xl overflow-hidden border border-slate-200/50 dark:border-slate-700/50">
                        <div className="px-8 py-12 sm:px-12">
                            {/* Header Section */}
                            <div className="text-center mb-16">
                                <div className="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl mb-6">
                                    <Scale className="h-8 w-8 text-white" />
                                </div>
                                <h1 className="text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-6">
                                    Terms of Service
                                </h1>
                                <p className="text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed">
                                    The terms and conditions for using EhtisabDaily - your comprehensive Islamic productivity platform
                                </p>
                            </div>
                            
                            {/* Last Updated Badge */}
                            <div className="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 border border-blue-200/50 dark:border-blue-800/50 rounded-2xl p-6 mb-16">
                                <div className="flex items-center gap-3">
                                    <div className="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                        <AlertCircle className="h-4 w-4 text-white" />
                                    </div>
                                    <div>
                                        <p className="text-slate-700 dark:text-slate-300 font-medium">
                                            <span className="text-blue-700 dark:text-blue-300">Last updated:</span> {new Date().toLocaleDateString('en-US', { 
                                                year: 'numeric', 
                                                month: 'long', 
                                                day: 'numeric' 
                                            })}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div className="space-y-16">
                                {/* Section 1: Acceptance of Terms */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-blue-500 to-purple-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                                                <Scale className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">1. Acceptance of Terms</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            By accessing and using Ehtisab Daily ("the Service"), you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 2: Description of Service */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-green-500 to-blue-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-8">
                                            <div className="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-500 rounded-xl flex items-center justify-center">
                                                <BookOpen className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">2. Description of Service</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 mb-6 leading-relaxed">
                                            Ehtisab Daily is a comprehensive Islamic productivity and financial management application that provides:
                                        </p>
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div className="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
                                                <h3 className="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-3">
                                                    <Users className="h-5 w-5 text-green-500" />
                                                    Spiritual Features
                                                </h3>
                                                <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Spiritual journey tracking and progress monitoring</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Morning and evening adhkar (remembrances)</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Dua (supplication) management and tracking</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div className="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-6 border border-slate-200 dark:border-slate-700">
                                                <h3 className="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-3">
                                                    <Settings className="h-5 w-5 text-blue-500" />
                                                    Financial Features
                                                </h3>
                                                <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Financial asset management and tracking</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Investment and financial planning tools</span>
                                                    </li>
                                                    <li className="flex items-start gap-3">
                                                        <div className="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                                                        <span>Secure data storage and privacy protection</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                {/* Section 3: User Accounts */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-purple-500 to-pink-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                                <User className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">3. User Accounts</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 mb-6 leading-relaxed">
                                            To access certain features of the Service, you must create an account. You agree to:
                                        </p>
                                        <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Provide accurate and complete information</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Maintain the security of your account credentials</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Be responsible for all activities under your account</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Notify us immediately of any unauthorized use</span>
                                            </li>
                                        </ul>
                                    </div>
                                </section>

                                {/* Section 4: Acceptable Use */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-orange-500 to-red-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center">
                                                <AlertTriangle className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">4. Acceptable Use</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 mb-6 leading-relaxed">
                                            You agree to use the Service only for lawful purposes and in accordance with these Terms. You agree not to:
                                        </p>
                                        <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Violate any applicable laws or regulations</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Transmit any harmful or malicious code</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Attempt to gain unauthorized access to the Service</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Interfere with the proper functioning of the Service</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-orange-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>Use the Service for any commercial purpose without permission</span>
                                            </li>
                                        </ul>
                                    </div>
                                </section>

                                {/* Section 5: Financial Data and Security */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-green-500 to-emerald-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                                                <Lock className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">5. Financial Data and Security</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 mb-6 leading-relaxed">
                                            Our Service includes financial management features. You acknowledge that:
                                        </p>
                                        <ul className="space-y-3 text-slate-700 dark:text-slate-300">
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>You are responsible for the accuracy of your financial data</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>We implement security measures to protect your data</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>You should maintain backups of important financial information</span>
                                            </li>
                                            <li className="flex items-start gap-3">
                                                <div className="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                                <span>We are not responsible for financial decisions made based on the Service</span>
                                            </li>
                                        </ul>
                                    </div>
                                </section>

                                {/* Section 6: Privacy and Data Protection */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-blue-500 to-cyan-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                                                <Shield className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">6. Privacy and Data Protection</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            Your privacy is important to us. Our collection and use of personal information is governed by our Privacy Policy, which is incorporated into these Terms by reference.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 7: Intellectual Property */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-indigo-500 to-purple-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center">
                                                <FileText className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">7. Intellectual Property</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            The Service and its original content, features, and functionality are owned by Ehtisab Daily and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 8: Disclaimers */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-pink-500 to-rose-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl flex items-center justify-center">
                                                <AlertTriangle className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">8. Disclaimers</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            The Service is provided on an "AS IS" and "AS AVAILABLE" basis. We make no warranties, expressed or implied, and hereby disclaim all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 9: Limitation of Liability */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl flex items-center justify-center">
                                                <Shield className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">9. Limitation of Liability</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            In no event shall Ehtisab Daily, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential, or punitive damages, including without limitation, loss of profits, data, use, goodwill, or other intangible losses, resulting from your use of the Service.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 10: Termination */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-slate-500 to-slate-600 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-slate-500 to-slate-600 rounded-xl flex items-center justify-center">
                                                <AlertTriangle className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">10. Termination</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            We may terminate or suspend your account and bar access to the Service immediately, without prior notice or liability, under our sole discretion, for any reason whatsoever and without limitation, including but not limited to a breach of the Terms.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 11: Governing Law */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-emerald-500 to-green-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-emerald-500 to-green-500 rounded-xl flex items-center justify-center">
                                                <Globe className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">11. Governing Law</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            These Terms shall be interpreted and governed by the laws of the jurisdiction in which Ehtisab Daily operates, without regard to its conflict of law provisions.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 12: Changes to Terms */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-amber-500 to-orange-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center">
                                                <FileText className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">12. Changes to Terms</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a revision is material, we will provide at least 30 days notice prior to any new terms taking effect.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 13: Severability */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-violet-500 to-purple-500 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-violet-500 to-purple-500 rounded-xl flex items-center justify-center">
                                                <Scale className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">13. Severability</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">
                                            If any provision of these Terms is held to be invalid or unenforceable by a court, the remaining provisions of these Terms will remain in effect.
                                        </p>
                                    </div>
                                </section>

                                {/* Section 14: Contact Information */}
                                <section className="group relative">
                                    <div className="absolute left-0 top-0 w-1 h-full bg-gradient-to-b from-slate-500 to-slate-600 rounded-full"></div>
                                    <div className="pl-8">
                                        <div className="flex items-center gap-4 mb-6">
                                            <div className="w-10 h-10 bg-gradient-to-br from-slate-500 to-slate-600 rounded-xl flex items-center justify-center">
                                                <Mail className="h-5 w-5 text-white" />
                                            </div>
                                            <h2 className="text-3xl font-bold text-slate-900 dark:text-slate-100">14. Contact Information</h2>
                                        </div>
                                        <p className="text-lg text-slate-700 dark:text-slate-300 mb-6 leading-relaxed">
                                            If you have any questions about these Terms of Service, please contact us:
                                        </p>
                                        <div className="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700 p-8 rounded-2xl border border-slate-200 dark:border-slate-600">
                                            <div className="flex items-start gap-4">
                                                <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                                    <Mail className="h-6 w-6 text-white" />
                                                </div>
                                                <div className="space-y-3">
                                                    <div>
                                                        <span className="font-semibold text-slate-900 dark:text-slate-100">Email:</span>
                                                        <span className="ml-2 text-slate-700 dark:text-slate-300">legal@ehtisabdaily.com</span>
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
                                            These terms of service are effective as of the date listed above and will remain in effect except with respect to any changes in their provisions in the future, which will be in effect immediately after being posted on this page.
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