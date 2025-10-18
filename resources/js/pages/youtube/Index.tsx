import { Head } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import { Button } from '@/components/ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Play, Search } from 'lucide-react';
import EhtisabPlay from './EhtisabPlay';
import SearchTab from './Search';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'My YouTube',
        href: '/my-youtube',
    },
];

interface IndexPageProps {
    needsGoogleAuth: boolean;
    error?: string;
}

export default function Index({ needsGoogleAuth, error }: IndexPageProps) {
    const [activeTab, setActiveTab] = useState('search');

    // Handle URL parameters for navigation
    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        const position = urlParams.get('position');
        const search = urlParams.get('search');

        if (tab && (tab === 'search' || tab === 'ehtisab-play')) {
            setActiveTab(tab);
        }

        // Clean up URL parameters after setting the tab
        if (tab || position || search) {
            const newUrl = new URL(window.location.href);
            newUrl.searchParams.delete('tab');
            newUrl.searchParams.delete('position');
            newUrl.searchParams.delete('search');
            window.history.replaceState({}, '', newUrl.toString());
        }
    }, []);

    const handleGoogleAuth = () => {
        window.location.href = '/auth/google';
    };

    if (needsGoogleAuth) {
        return (
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="My YouTube - Authentication Required" />
                
                <div className="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
                    <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center">
                            <div className="mx-auto h-12 w-12 text-red-500 mb-4">
                                <svg fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </div>
                            
                            <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                                My YouTube
                            </h1>
                            
                            <p className="text-lg text-gray-600 dark:text-gray-300 mb-8">
                                {error || 'Please authenticate with Google to access YouTube features.'}
                            </p>
                            
                            <Button onClick={handleGoogleAuth} className="bg-red-600 hover:bg-red-700 text-white">
                                <svg className="mr-2 h-4 w-4" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                </svg>
                                Continue with Google
                            </Button>
                        </div>
                    </div>
                </div>
            </AppLayout>
        );
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="My YouTube" />
            
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-6">
                <div className="mb-6">
                    <h1 className="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        My YouTube
                    </h1>
                    <p className="text-gray-600 dark:text-gray-300">
                        Access your EhtisabDaily playlist and search YouTube videos.
                    </p>
                </div>

                <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
                    <TabsList className="grid w-full grid-cols-2">
                        <TabsTrigger value="search" className="flex items-center gap-2">
                            <Search className="h-4 w-4" />
                            Search
                        </TabsTrigger>
                        <TabsTrigger value="ehtisab-play" className="flex items-center gap-2">
                            <Play className="h-4 w-4" />
                            Ehtisab Play
                        </TabsTrigger>
                    </TabsList>

                    <TabsContent value="search" className="mt-4">
                        <SearchTab />
                    </TabsContent>

                    <TabsContent value="ehtisab-play" className="mt-4">
                        <EhtisabPlay />
                    </TabsContent>
                </Tabs>
            </div>
        </AppLayout>
    );
}
