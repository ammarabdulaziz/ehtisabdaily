import { Head, Link } from '@inertiajs/react';
import { Heart, ArrowLeft } from 'lucide-react';
import { type ReactNode } from 'react';

interface PublicLayoutProps {
    children: ReactNode;
    title: string;
    showBackButton?: boolean;
}

export default function PublicLayout({ children, title, showBackButton = true }: PublicLayoutProps) {
    return (
        <div className="min-h-screen bg-gradient-to-br from-amber-50 to-emerald-50 dark:from-slate-900 dark:to-emerald-950">
            <Head title={title} />
            
            {/* Header */}
            <header className="bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm border-b border-slate-200 dark:border-slate-700 sticky top-0 z-50">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex items-center justify-between h-16">
                        <div className="flex items-center gap-3">
                            {showBackButton && (
                                <Link 
                                    href="/home" 
                                    className="flex items-center gap-2 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors"
                                >
                                    <ArrowLeft className="h-4 w-4" />
                                    <span className="text-sm">Back to Home</span>
                                </Link>
                            )}
                        </div>
                        <Link href="/home" className="flex items-center gap-3">
                            <div className="w-8 h-8 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-lg flex items-center justify-center">
                                <Heart className="h-5 w-5 text-white" />
                            </div>
                            <span className="text-xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                                EhtisabDaily
                            </span>
                        </Link>
                    </div>
                </div>
            </header>

            {/* Main Content */}
            <main className="flex-1">
                {children}
            </main>

            {/* Footer */}
            <footer className="border-t border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div className="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div className="flex items-center gap-3">
                            <div className="w-6 h-6 bg-gradient-to-br from-emerald-600 to-teal-600 rounded-lg flex items-center justify-center">
                                <Heart className="h-4 w-4 text-white" />
                            </div>
                            <span className="text-sm text-slate-600 dark:text-slate-300">
                                © 2025 EhtisabDaily. All rights reserved.
                            </span>
                        </div>
                        <div className="flex gap-6">
                            <Link href="/privacy-policy" className="text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                                Privacy Policy
                            </Link>
                            <Link href="/terms-of-service" className="text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                                Terms of Service
                            </Link>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    );
}
