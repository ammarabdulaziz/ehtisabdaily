import { useState, useEffect } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Loader2, Lock, AlertTriangle } from 'lucide-react';
import { useGlobalSecurity } from '@/contexts/GlobalSecurityContext';

interface GlobalSecurityModalProps {
  isOpen: boolean;
  onClose: () => void;
  onGoToDashboard?: () => void;
}

export default function GlobalSecurityModal({ 
  isOpen, 
  onClose,
  onGoToDashboard
}: GlobalSecurityModalProps) {
  const [securityCode, setSecurityCode] = useState('');
  const [error, setError] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const { unlock } = useGlobalSecurity();

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
      const success = await unlock(securityCode);
      
      if (success) {
        onClose();
        // Redirect to intended URL or dashboard
        const intendedUrl = localStorage.getItem('url.intended') || '/dashboard';
        window.location.href = intendedUrl;
      } else {
        setError('Invalid security code');
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
      window.location.href = '/dashboard';
    }
  };

  return (
    <Dialog open={isOpen} onOpenChange={handleGoToDashboard}>
      <DialogContent className="sm:max-w-md">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <Lock className="h-5 w-5 text-red-600" />
            Global Security Verification
          </DialogTitle>
        </DialogHeader>
        
        <div className="space-y-6">
          <div className="text-center">
            <div className="mx-auto mb-4 w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center">
              <Lock className="h-6 w-6 text-red-600" />
            </div>
            <p className="text-sm text-gray-600 dark:text-gray-400">
              Enter the 5-digit security code to access protected features
            </p>
          </div>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor="securityCode">Security Code</Label>
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
                autoFocus
              />
              {error && (
                <Alert variant="destructive">
                  <AlertTriangle className="h-4 w-4" />
                  <AlertDescription>{error}</AlertDescription>
                </Alert>
              )}
            </div>

            <div className="flex items-center justify-between">
              <button 
                type="button" 
                onClick={handleGoToDashboard}
                className="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200"
              >
                Go to Dashboard
              </button>
              
              <span className="text-xs text-gray-500 dark:text-gray-400">
                Valid for 1 hour
              </span>
            </div>

            <Button 
              type="submit"
              className="w-full"
              disabled={isSubmitting || securityCode.length !== 5}
            >
              {isSubmitting ? (
                <>
                  <Loader2 className="mr-2 h-4 w-4 animate-spin" />
                  Verifying...
                </>
              ) : (
                'Verify Code'
              )}
            </Button>
          </form>
        </div>
      </DialogContent>
    </Dialog>
  );
}
