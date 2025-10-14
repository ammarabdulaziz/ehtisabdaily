import React, { useState, useEffect } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Shield } from 'lucide-react';
import { router } from '@inertiajs/react';

interface AssetsSecurityModalProps {
    isOpen: boolean;
    onClose: () => void;
    onGoToDashboard?: () => void;
}

export default function AssetsSecurityModal({ 
    isOpen, 
    onClose,
    onGoToDashboard
}: AssetsSecurityModalProps) {
    const [securityCode, setSecurityCode] = useState('');
    const [error, setError] = useState('');
    const [isSubmitting, setIsSubmitting] = useState(false);

    useEffect(() => {
        if (isOpen) {
            setSecurityCode('');
            setError('');
        }
    }, [isOpen]);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        
        if (securityCode.length !== 5) {
            setError('Please enter a 5-digit security code');
            return;
        }

        setIsSubmitting(true);
        setError('');

        try {
            const response = await fetch('/api/assets/verify-security', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({ security_code: securityCode }),
            });

            const data = await response.json();

            if (response.ok) {
                onClose();
                // Redirect to assets page after successful verification
                window.location.href = '/assets';
            } else {
                setError(data.message || 'Invalid security code');
            }
        } catch (error) {
            setError('An error occurred. Please try again.');
        } finally {
            setIsSubmitting(false);
        }
    };


    const handleGoToDashboard = () => {
        if (onGoToDashboard) {
            onGoToDashboard();
        } else {
            router.visit('/dashboard');
        }
    };

    return (
        <Dialog open={isOpen} onOpenChange={handleGoToDashboard}>
            <DialogContent className="sm:max-w-md">
                <DialogHeader className="text-center">
                    <div className="mx-auto mb-4 p-3 rounded-full bg-red-100 dark:bg-red-900/20 w-fit">
                        <Shield className="h-8 w-8 text-red-600 dark:text-red-400" />
                    </div>
                    <DialogTitle className="text-2xl">Security Required</DialogTitle>
                    <DialogDescription>
                        Enter the 5-digit security code to access assets
                    </DialogDescription>
                </DialogHeader>

                <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="space-y-2">
                        <label htmlFor="securityCode" className="text-sm font-medium">
                            Security Code
                        </label>
                        <Input
                            id="securityCode"
                            type="text"
                            inputMode="numeric"
                            value={securityCode}
                            onChange={(e) => {
                                const value = e.target.value.replace(/\D/g, '').slice(0, 5);
                                setSecurityCode(value);
                                setError('');
                            }}
                            placeholder="00000"
                            className="text-center text-lg tracking-widest"
                            maxLength={5}
                            autoComplete="off"
                        />
                        {error && (
                            <p className="text-sm text-red-600 dark:text-red-400">{error}</p>
                        )}
                    </div>

                    <div className="flex items-center justify-center">
                        <span className="text-xs text-muted-foreground">
                            Valid for 1 hour
                        </span>
                    </div>

                    <Button 
                        type="submit" 
                        className="w-full" 
                        disabled={isSubmitting || securityCode.length !== 5}
                    >
                        {isSubmitting ? 'Verifying...' : 'Verify Code'}
                    </Button>
                </form>
            </DialogContent>
        </Dialog>
    );
}
