import { createContext, useContext, useState, useEffect, useCallback, ReactNode } from 'react';

interface GlobalSecurityContextType {
  isLocked: boolean;
  isAccessible: boolean;
  isLoading: boolean;
  checkStatus: () => Promise<void>;
  toggleLock: () => Promise<void>;
  unlock: (securityCode: string) => Promise<boolean>;
}

const GlobalSecurityContext = createContext<GlobalSecurityContextType | undefined>(undefined);

interface GlobalSecurityProviderProps {
  children: ReactNode;
}

export function GlobalSecurityProvider({ children }: GlobalSecurityProviderProps) {
  const [isLocked, setIsLocked] = useState(false);
  const [isAccessible, setIsAccessible] = useState(true);
  const [isLoading, setIsLoading] = useState(true);

  const checkStatus = useCallback(async () => {
    // Skip API call if we're on login/register pages
    const currentPath = window.location.pathname;
    if (currentPath === '/login' || currentPath === '/register' || currentPath.startsWith('/forgot-password') || currentPath.startsWith('/reset-password')) {
      setIsLocked(false);
      setIsAccessible(true);
      setIsLoading(false);
      return;
    }

    try {
      const response = await fetch('/api/global-security/status');
      if (response.ok) {
        const data = await response.json();
        setIsLocked(data.is_locked);
        setIsAccessible(data.is_accessible);
      } else if (response.status === 401 || response.status === 403) {
        // User is not authenticated, so global security doesn't apply
        setIsLocked(false);
        setIsAccessible(true);
      }
    } catch (error) {
      // If the request fails (e.g., user not authenticated), assume no security restrictions
      setIsLocked(false);
      setIsAccessible(true);
    } finally {
      setIsLoading(false);
    }
  }, []);

  const toggleLock = useCallback(async () => {
    try {
      const response = await fetch('/api/global-security/toggle', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
      });

      if (response.ok) {
        const data = await response.json();
        setIsLocked(data.is_locked);
        setIsAccessible(!data.is_locked);
      }
    } catch (error) {
      console.error('Failed to toggle global security:', error);
    }
  }, []);

  const unlock = useCallback(async (securityCode: string): Promise<boolean> => {
    try {
      const response = await fetch('/api/global-security/verify', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
        body: JSON.stringify({ security_code: securityCode }),
      });

      if (response.ok) {
        const data = await response.json();
        if (data.success) {
          setIsLocked(false);
          setIsAccessible(true);
          return true;
        }
      }
      return false;
    } catch (error) {
      console.error('Failed to unlock global security:', error);
      return false;
    }
  }, []);

  // Check status on mount
  useEffect(() => {
    checkStatus();
  }, [checkStatus]);

  // Check status when tab becomes visible (replaces periodic polling)
  useEffect(() => {
    const handleVisibilityChange = () => {
      if (!document.hidden) {
        checkStatus();
      }
    };
    
    document.addEventListener('visibilitychange', handleVisibilityChange);
    return () => document.removeEventListener('visibilitychange', handleVisibilityChange);
  }, [checkStatus]);

  const value: GlobalSecurityContextType = {
    isLocked,
    isAccessible,
    isLoading,
    checkStatus,
    toggleLock,
    unlock,
  };

  return (
    <GlobalSecurityContext.Provider value={value}>
      {children}
    </GlobalSecurityContext.Provider>
  );
}

export function useGlobalSecurity() {
  const context = useContext(GlobalSecurityContext);
  if (context === undefined) {
    throw new Error('useGlobalSecurity must be used within a GlobalSecurityProvider');
  }
  return context;
}
