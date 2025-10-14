import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import GlobalSecurityModal from '@/components/GlobalSecurityModal';
import { useState, useEffect } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Security Verification',
        href: '/global-security',
    },
];

export default function GlobalSecurity() {
    const [showModal, setShowModal] = useState(true);

    useEffect(() => {
        // Show modal immediately when page loads
        setShowModal(true);
    }, []);

    const handleClose = () => {
        setShowModal(false);
        // Redirect to dashboard after closing
        window.location.href = '/dashboard';
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Security Verification" />
            <div className="flex h-full flex-1 flex-col items-center justify-center">
                <div className="text-center">
                    <h1 className="text-2xl font-semibold text-gray-900 dark:text-white mb-2">
                        Security Verification Required
                    </h1>
                    <p className="text-gray-600 dark:text-gray-400">
                        Please verify your identity to access protected features.
                    </p>
                </div>
            </div>
            
            <GlobalSecurityModal
                isOpen={showModal}
                onClose={handleClose}
            />
        </AppLayout>
    );
}
