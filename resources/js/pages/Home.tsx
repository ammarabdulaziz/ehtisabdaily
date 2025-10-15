import { Card, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { dashboard, login, register, privacyPolicy, termsOfService } from '@/routes';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { 
    BookMarked, 
    Sun, 
    Moon, 
    Calculator, 
    Heart, 
    Star, 
    Shield, 
    Clock,
    ArrowRight,
    Sparkles
} from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Home',
        href: '/home',
    },
];

export default function Home() {
    const { auth } = usePage<SharedData>().props;
    const isAuthenticated = !!auth.user;

    // Guest user view
    if (!isAuthenticated) {
        return (
            <>
                <Head title="EhtisabDaily - Islamic Productivity Platform" />
                <div className="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-900 dark:to-slate-800">
                    {/* Header */}
                    <header className="border-b border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm">
                        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            <div className="flex justify-between items-center h-16">
                                <div className="flex items-center gap-3">
                                    <div className="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                        <Heart className="h-5 w-5 text-white" />
                                    </div>
                                    <span className="text-xl font-bold text-slate-900 dark:text-white">
                                        EhtisabDaily
                                    </span>
                                </div>
                                <div className="flex items-center gap-4">
                                    <Link href={login()}>
                                        <Button variant="ghost">Sign In</Button>
                                    </Link>
                                    <Link href={register()}>
                                        <Button>Get Started</Button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </header>

                    {/* Hero Section */}
                    <section className="py-20 px-4 sm:px-6 lg:px-8">
                        <div className="max-w-7xl mx-auto text-center">
                            <div className="flex items-center justify-center gap-2 mb-6">
                                <Sparkles className="h-6 w-6 text-blue-600" />
                                <span className="text-sm font-medium text-blue-600 dark:text-blue-400">
                                    Islamic Productivity Platform
                                </span>
                            </div>
                            <h1 className="text-4xl md:text-6xl font-bold text-slate-900 dark:text-white mb-6">
                                Strengthen Your
                                <span className="block bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                                    Spiritual Journey
                                </span>
                            </h1>
                            <p className="text-xl text-slate-600 dark:text-slate-300 mb-8 max-w-3xl mx-auto">
                                A comprehensive platform for Muslims to track their daily spiritual practices, 
                                manage their hisabat, and grow closer to Allah through consistent remembrance.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link href={register()}>
                                    <Button size="lg" className="w-full sm:w-auto">
                                        Start Your Journey
                                        <ArrowRight className="ml-2 h-4 w-4" />
                                    </Button>
                                </Link>
                                <Link href={login()}>
                                    <Button variant="outline" size="lg" className="w-full sm:w-auto">
                                        Sign In
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </section>

                    {/* Features Section */}
                    <section className="py-20 px-4 sm:px-6 lg:px-8 bg-white/50 dark:bg-slate-800/50">
                        <div className="max-w-7xl mx-auto">
                            <div className="text-center mb-16">
                                <h2 className="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                                    Everything You Need for Spiritual Growth
                                </h2>
                                <p className="text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
                                    Our platform provides all the tools you need to maintain consistency in your Islamic practices
                                </p>
                            </div>
                            
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                                {/* Morning Adhkar */}
                                <Card className="group hover:shadow-lg transition-all duration-300 border-0 bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20">
                                    <CardHeader className="text-center">
                                        <div className="mx-auto mb-4 p-3 rounded-full bg-orange-100 dark:bg-orange-900/30 w-fit">
                                            <Sun className="h-8 w-8 text-orange-600 dark:text-orange-400" />
                                        </div>
                                        <CardTitle className="text-xl">Morning Adhkar</CardTitle>
                                        <CardDescription>
                                            Start your day with beautiful morning remembrances and supplications
                                        </CardDescription>
                                    </CardHeader>
                                </Card>

                                {/* Evening Adhkar */}
                                <Card className="group hover:shadow-lg transition-all duration-300 border-0 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20">
                                    <CardHeader className="text-center">
                                        <div className="mx-auto mb-4 p-3 rounded-full bg-indigo-100 dark:bg-indigo-900/30 w-fit">
                                            <Moon className="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                                        </div>
                                        <CardTitle className="text-xl">Evening Adhkar</CardTitle>
                                        <CardDescription>
                                            End your day with peaceful evening remembrances and reflections
                                        </CardDescription>
                                    </CardHeader>
                                </Card>

                                {/* Duas Collection */}
                                <Card className="group hover:shadow-lg transition-all duration-300 border-0 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                                    <CardHeader className="text-center">
                                        <div className="mx-auto mb-4 p-3 rounded-full bg-green-100 dark:bg-green-900/30 w-fit">
                                            <BookMarked className="h-8 w-8 text-green-600 dark:text-green-400" />
                                        </div>
                                        <CardTitle className="text-xl">Duas Collection</CardTitle>
                                        <CardDescription>
                                            Access a comprehensive collection of Islamic supplications
                                        </CardDescription>
                                    </CardHeader>
                                </Card>

                                {/* Hisabat Management */}
                                <Card className="group hover:shadow-lg transition-all duration-300 border-0 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20">
                                    <CardHeader className="text-center">
                                        <div className="mx-auto mb-4 p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 w-fit">
                                            <Calculator className="h-8 w-8 text-blue-600 dark:text-blue-400" />
                                        </div>
                                        <CardTitle className="text-xl">Hisabat Management</CardTitle>
                                        <CardDescription>
                                            Track and manage your personal accountability and spiritual progress
                                        </CardDescription>
                                    </CardHeader>
                                </Card>
                            </div>
                        </div>
                    </section>

                    {/* Mission Section */}
                    <section className="py-20 px-4 sm:px-6 lg:px-8">
                        <div className="max-w-4xl mx-auto text-center">
                            <div className="flex items-center justify-center gap-2 mb-6">
                                <Star className="h-6 w-6 text-amber-500" />
                                <span className="text-sm font-medium text-amber-600 dark:text-amber-400">
                                    Our Mission
                                </span>
                            </div>
                            <h2 className="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-6">
                                Building a Stronger Ummah Through Technology
                            </h2>
                            <p className="text-lg text-slate-600 dark:text-slate-300 mb-8">
                                We believe that technology can be a powerful tool for spiritual growth. Our platform 
                                combines traditional Islamic practices with modern productivity techniques to help 
                                Muslims maintain consistency in their worship and personal development.
                            </p>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                                <div className="text-center">
                                    <div className="mx-auto mb-4 p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 w-fit">
                                        <Heart className="h-8 w-8 text-blue-600 dark:text-blue-400" />
                                    </div>
                                    <h3 className="text-xl font-semibold text-slate-900 dark:text-white mb-2">
                                        Spiritual Growth
                                    </h3>
                                    <p className="text-slate-600 dark:text-slate-300">
                                        Consistent practice leads to spiritual development
                                    </p>
                                </div>
                                <div className="text-center">
                                    <div className="mx-auto mb-4 p-3 rounded-full bg-green-100 dark:bg-green-900/30 w-fit">
                                        <Shield className="h-8 w-8 text-green-600 dark:text-green-400" />
                                    </div>
                                    <h3 className="text-xl font-semibold text-slate-900 dark:text-white mb-2">
                                        Accountability
                                    </h3>
                                    <p className="text-slate-600 dark:text-slate-300">
                                        Track your progress and maintain self-discipline
                                    </p>
                                </div>
                                <div className="text-center">
                                    <div className="mx-auto mb-4 p-3 rounded-full bg-purple-100 dark:bg-purple-900/30 w-fit">
                                        <Clock className="h-8 w-8 text-purple-600 dark:text-purple-400" />
                                    </div>
                                    <h3 className="text-xl font-semibold text-slate-900 dark:text-white mb-2">
                                        Daily Habits
                                    </h3>
                                    <p className="text-slate-600 dark:text-slate-300">
                                        Build lasting habits that strengthen your faith
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* Footer */}
                    <footer className="border-t border-slate-200 dark:border-slate-700 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm">
                        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                            <div className="flex flex-col md:flex-row justify-between items-center gap-4">
                                <div className="flex items-center gap-3">
                                    <div className="w-6 h-6 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                                        <Heart className="h-4 w-4 text-white" />
                                    </div>
                                    <span className="text-sm text-slate-600 dark:text-slate-300">
                                        © 2024 EhtisabDaily. All rights reserved.
                                    </span>
                                </div>
                                <div className="flex gap-6">
                                    <Link href={privacyPolicy()} className="text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                                        Privacy Policy
                                    </Link>
                                    <Link href={termsOfService()} className="text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                                        Terms of Service
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
            </>
        );
    }

    // Authenticated user view
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Home" />
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
                {/* Welcome Section */}
                <div className="text-center py-8">
                    <h1 className="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                        Welcome back, {auth.user.name}! 👋
                    </h1>
                    <p className="text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">
                        Ready to continue your spiritual journey? Choose where you'd like to start today.
                    </p>
                </div>

                {/* Quick Access Navigation */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {/* Dashboard */}
                    <Link href={dashboard()}>
                        <Card className="group hover:shadow-lg transition-all duration-300 cursor-pointer h-full border-0 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700">
                            <CardHeader className="text-center pb-4">
                                <div className="mx-auto mb-4 p-3 rounded-full bg-slate-100 dark:bg-slate-700 w-fit">
                                    <Star className="h-8 w-8 text-slate-600 dark:text-slate-400" />
                                </div>
                                <CardTitle className="text-xl">Dashboard</CardTitle>
                                <CardDescription>
                                    View your progress and spiritual journey overview
                                </CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>

                    {/* Morning Adhkar */}
                    <Link href="/morning-adhkar">
                        <Card className="group hover:shadow-lg transition-all duration-300 cursor-pointer h-full border-0 bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20">
                            <CardHeader className="text-center pb-4">
                                <div className="mx-auto mb-4 p-3 rounded-full bg-orange-100 dark:bg-orange-900/30 w-fit">
                                    <Sun className="h-8 w-8 text-orange-600 dark:text-orange-400" />
                                </div>
                                <CardTitle className="text-xl">Morning Adhkar</CardTitle>
                                <CardDescription>
                                    Start your day with beautiful morning remembrances
                                </CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>

                    {/* Evening Adhkar */}
                    <Link href="/evening-adhkar">
                        <Card className="group hover:shadow-lg transition-all duration-300 cursor-pointer h-full border-0 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20">
                            <CardHeader className="text-center pb-4">
                                <div className="mx-auto mb-4 p-3 rounded-full bg-indigo-100 dark:bg-indigo-900/30 w-fit">
                                    <Moon className="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                                </div>
                                <CardTitle className="text-xl">Evening Adhkar</CardTitle>
                                <CardDescription>
                                    End your day with peaceful evening remembrances
                                </CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>

                    {/* Duas */}
                    <Link href="/duas">
                        <Card className="group hover:shadow-lg transition-all duration-300 cursor-pointer h-full border-0 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                            <CardHeader className="text-center pb-4">
                                <div className="mx-auto mb-4 p-3 rounded-full bg-green-100 dark:bg-green-900/30 w-fit">
                                    <BookMarked className="h-8 w-8 text-green-600 dark:text-green-400" />
                                </div>
                                <CardTitle className="text-xl">Duas</CardTitle>
                                <CardDescription>
                                    Access comprehensive Islamic supplications
                                </CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>

                    {/* Hisabat Management */}
                    <Link href="/hisabat" className="block">
                        <Card className="group hover:shadow-lg transition-all duration-300 cursor-pointer h-full border-0 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20">
                            <CardHeader className="text-center pb-4">
                                <div className="mx-auto mb-4 p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 w-fit">
                                    <Calculator className="h-8 w-8 text-blue-600 dark:text-blue-400" />
                                </div>
                                <CardTitle className="text-xl">Hisabat Management</CardTitle>
                                <CardDescription>
                                    Track your personal accountability and progress
                                </CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>

                    {/* Quick Stats or Motivational Card */}
                    <Card className="group hover:shadow-lg transition-all duration-300 h-full border-0 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                        <CardHeader className="text-center pb-4">
                            <div className="mx-auto mb-4 p-3 rounded-full bg-purple-100 dark:bg-purple-900/30 w-fit">
                                <Heart className="h-8 w-8 text-purple-600 dark:text-purple-400" />
                            </div>
                            <CardTitle className="text-xl">Keep Going!</CardTitle>
                            <CardDescription>
                                Every step you take in your spiritual journey brings you closer to Allah. Stay consistent and trust the process.
                            </CardDescription>
                        </CardHeader>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
