import React, { useState, useEffect } from 'react';
import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import AssetsSecurityModal from '@/components/AssetsSecurityModal';

export default function AssetsSecurity() {
    const [isModalOpen, setIsModalOpen] = useState(true);

    useEffect(() => {
        // Check if user already has a valid session
        const checkSecurityStatus = async () => {
            try {
                const response = await fetch('/api/assets/security-status');
                if (response.ok) {
                    const data = await response.json();
                    
                    // If user already has a valid session and is not locked, redirect to assets
                    if (data.has_valid_session && !data.is_locked) {
                        window.location.href = '/assets';
                    }
                }
            } catch (error) {
                console.error('Failed to check security status:', error);
            }
        };

        checkSecurityStatus();
    }, []);

    const handleModalClose = () => {
        // Don't allow closing the modal - user must enter security code
        // setIsModalOpen(false);
    };


    return (
        <AppLayout>
            <Head title="Assets Security" />
            
            <div className="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900">
                <div className="text-center">
                    <h1 className="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        Accessing Assets
                    </h1>
                    <p className="text-gray-600 dark:text-gray-400">
                        Please enter your security code to continue
                    </p>
                </div>
            </div>

            <AssetsSecurityModal
                isOpen={isModalOpen}
                onClose={handleModalClose}
            />
        </AppLayout>
    );
}
