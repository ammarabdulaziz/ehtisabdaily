import { Card, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Calculator, BookMarked, Wallet, Sun, Moon } from 'lucide-react';
import ProgressTracker from '@/components/ProgressTracker';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

export default function Dashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">

                {/* Progress Tracker */}
                <ProgressTracker useFallback={true} />

                {/* Navigation Cards */}
                <div className="grid gap-6 md:grid-cols-3">
                    {/* Manage Hisabat Card */}
                    <a href="/hisabat" rel="noopener noreferrer" className="block">
                        <Card className="group hover:shadow-md transition-all duration-300 cursor-pointer h-full">
                            <CardHeader className="text-center pb-4">
                                <div className="mx-auto mb-4 p-3 rounded-full bg-blue-100 dark:bg-blue-900/20 w-fit">
                                    <Calculator className="h-8 w-8 text-blue-600 dark:text-blue-400" />
                                </div>
                                <CardTitle className="text-xl">Manage Hisabat</CardTitle>
                                <CardDescription>
                                    Hisabat Mnagement Dashboard
                                </CardDescription>
                            </CardHeader>
                        </Card>
                    </a>

                    {/* Morning Adhkar Card */}
                    <Link href="/morning-adhkar" className="block">
                        <Card className="group hover:shadow-md transition-all duration-300 cursor-pointer h-full">
                            <CardHeader className="text-center pb-4">
                                <div className="mx-auto mb-4 p-3 rounded-full bg-orange-100 dark:bg-orange-900/20 w-fit">
                                    <Sun className="h-8 w-8 text-orange-600 dark:text-orange-400" />
                                </div>
                                <CardTitle className="text-xl">Morning Adhkar</CardTitle>
                                <CardDescription>
                                    Daily Morning Remembrances
                                </CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>

                    {/* Evening Adhkar Card */}
                    <Link href="/evening-adhkar" className="block">
                        <Card className="group hover:shadow-md transition-all duration-300 cursor-pointer h-full">
                            <CardHeader className="text-center pb-4">
                                <div className="mx-auto mb-4 p-3 rounded-full bg-indigo-100 dark:bg-indigo-900/20 w-fit">
                                    <Moon className="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                                </div>
                                <CardTitle className="text-xl">Evening Adhkar</CardTitle>
                                <CardDescription>
                                    Daily Evening Remembrances
                                </CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>
                </div>

                {/* Additional Navigation Cards */}
                <div className="grid gap-6 md:grid-cols-2">
                    {/* Duas Card */}
                    <Link href="/duas" className="block">
                        <Card className="group hover:shadow-md transition-all duration-300 cursor-pointer h-full">
                            <CardHeader className="text-center pb-4">
                                <div className="mx-auto mb-4 p-3 rounded-full bg-green-100 dark:bg-green-900/20 w-fit">
                                    <BookMarked className="h-8 w-8 text-green-600 dark:text-green-400" />
                                </div>
                                <CardTitle className="text-xl">Duas</CardTitle>
                                <CardDescription>
                                    Prominent Islamic Supplications
                                </CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>

                    {/* Assets Card */}
                    <Link href="/assets" className="block">
                        <Card className="group hover:shadow-md transition-all duration-300 cursor-pointer h-full">
                            <CardHeader className="text-center pb-4">
                                <div className="mx-auto mb-4 p-3 rounded-full bg-purple-100 dark:bg-purple-900/20 w-fit">
                                    <Wallet className="h-8 w-8 text-purple-600 dark:text-purple-400" />
                                </div>
                                <CardTitle className="text-xl">Assets</CardTitle>
                                <CardDescription>
                                    Personal Assets and Investments
                                </CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>
                </div>

            </div>
        </AppLayout>
    );
}
