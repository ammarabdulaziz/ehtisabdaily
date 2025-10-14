import { Breadcrumbs } from '@/components/breadcrumbs';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem as BreadcrumbItemType } from '@/types';
import { Lock, LockOpen, Loader2 } from 'lucide-react';
import { useState } from 'react';
import { useGlobalSecurity } from '@/contexts/GlobalSecurityContext';
import GlobalSecurityModal from '@/components/GlobalSecurityModal';

export function AppSidebarHeader({ breadcrumbs = [] }: { breadcrumbs?: BreadcrumbItemType[] }) {
    const { isLocked, isAccessible, isLoading, toggleLock } = useGlobalSecurity();
    const [showModal, setShowModal] = useState(false);

    const handleLockToggle = async () => {
        if (isLocked && !isAccessible) {
            // If locked and not accessible, show modal for verification
            setShowModal(true);
        } else {
            // If unlocked or accessible, toggle lock
            await toggleLock();
        }
    };

    return (
        <>
            <header className="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/50 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
                <div className="flex items-center gap-2">
                    <SidebarTrigger className="-ml-1" />
                    <Breadcrumbs breadcrumbs={breadcrumbs} />
                </div>
                
                {/* Global Security Lock Button */}
                <div className="ml-auto">
                    <Button
                        variant="ghost"
                        size="sm"
                        onClick={handleLockToggle}
                        disabled={isLoading}
                        className="flex items-center gap-2 text-sm"
                    >
                        {isLoading ? (
                            <Loader2 className="h-4 w-4 animate-spin" />
                        ) : isLocked && !isAccessible ? (
                            <Lock className="h-4 w-4 text-red-600" />
                        ) : (
                            <LockOpen className="h-4 w-4 text-green-600" />
                        )}
                        <span className="hidden sm:inline">
                            {isLocked && !isAccessible ? 'Unlock' : 'Lock'} Security
                        </span>
                    </Button>
                </div>
            </header>
            
            <GlobalSecurityModal
                isOpen={showModal}
                onClose={() => setShowModal(false)}
            />
        </>
    );
}
