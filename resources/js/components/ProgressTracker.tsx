import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Target, Calendar, Sparkles, Loader2 } from 'lucide-react';
import { useState, useEffect, useCallback } from 'react';

interface ProgressData {
  lastSkip: Date;
  targetDate: Date;
  currentDate: Date;
  totalDays: number;
  daysCompleted: number;
  percentage: number;
  daysRemaining: number;
}

interface MotivationalQuote {
  quote: string;
  type: 'islamic' | 'general' | 'realistic';
  context: string;
}

const milestones = [
  { days: 7, message: "One week strong! 🎉", color: "bg-green-500" },
  { days: 30, message: "One month milestone! 🏆", color: "bg-blue-500" },
  { days: 60, message: "Two months of growth! 🌟", color: "bg-purple-500" },
  { days: 90, message: "Three months of strength! 💎", color: "bg-yellow-500" },
  { days: 120, message: "Four months of progress! 🚀", color: "bg-pink-500" },
];

interface ProgressTrackerProps {
  useFallback?: boolean;
}

export default function ProgressTracker({ useFallback }: ProgressTrackerProps = {}) {
  const [progressData, setProgressData] = useState<ProgressData | null>(null);
  const [currentQuote, setCurrentQuote] = useState<MotivationalQuote | null>(null);
  const [isLoadingQuote, setIsLoadingQuote] = useState(false);
  const [showMilestone, setShowMilestone] = useState(false);
  const [milestoneMessage, setMilestoneMessage] = useState('');

  // Determine if we should use fallback quotes
  const shouldUseFallback = useFallback ?? (
    window.location.hostname === 'ehtisabdaily.test'
  );

  // const shouldUseFallback = false;

  const fetchMotivationalQuote = useCallback(async (daysCompleted: number, daysRemaining: number, percentage: number) => {
    console.log('🚀 Starting motivational quote fetch', {
      daysCompleted,
      daysRemaining,
      percentage,
      shouldUseFallback,
      timestamp: new Date().toISOString(),
    });

    setIsLoadingQuote(true);
    
    // Use fallback quotes if explicitly set or in local environment
    if (shouldUseFallback) {
      console.log('📝 Using fallback mode - skipping API call');
      // Simulate API delay
      setTimeout(() => {
        // Commented out fallback quotes - show message instead
        // const fallbackQuotes = [
        //   {
        //     quote: 'And whoever relies upon Allah - then He is sufficient for him.',
        //     type: 'islamic' as const,
        //     context: 'Trust in Allah\'s plan for you.',
        //   },
        //   {
        //     quote: 'Every step forward is a victory worth celebrating!',
        //     type: 'general' as const,
        //     context: 'Keep moving forward on your journey!',
        //   },
        //   {
        //     quote: 'Progress, not perfection - you\'re doing amazing!',
        //     type: 'realistic' as const,
        //     context: 'Focus on consistent progress.',
        //   },
        //   {
        //     quote: 'The best time to plant a tree was 20 years ago. The second best time is now.',
        //     type: 'realistic' as const,
        //     context: 'It\'s never too late to start your journey.',
        //   },
        //   {
        //     quote: 'And it is He who created the heavens and earth in truth. And the day He says, "Be," and it is.',
        //     type: 'islamic' as const,
        //     context: 'Allah\'s power is beyond our understanding.',
        //   },
        //   {
        //     quote: 'Success is not final, failure is not fatal: it is the courage to continue that counts.',
        //     type: 'general' as const,
        //     context: 'Persistence is the key to success.',
        //   },
        // ];
        
        // const randomQuote = fallbackQuotes[Math.floor(Math.random() * fallbackQuotes.length)];
        // setCurrentQuote(randomQuote);
        setCurrentQuote(null); // Show message instead of fallback quote
        setIsLoadingQuote(false);
      }, 800); // Simulate API delay
      return;
    }

    // Production: Use actual API
    try {
      const requestData = {
        days_completed: daysCompleted,
        days_remaining: daysRemaining,
        percentage: percentage,
      };

      console.log('📤 Sending API request', {
        url: '/api/motivational-quote',
        method: 'POST',
        data: requestData,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 'NOT_FOUND',
      });

      const response = await fetch('/api/motivational-quote', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        },
        body: JSON.stringify(requestData),
      });

      console.log('📥 Received API response', {
        status: response.status,
        statusText: response.statusText,
        ok: response.ok,
        headers: Object.fromEntries(response.headers.entries()),
        url: response.url,
      });

      if (response.ok) {
        const data = await response.json();
        console.log('✅ Successfully received quote data', {
          data,
          hasQuote: !!data.quote,
          quoteType: data.type,
          dataKeys: Object.keys(data),
        });
        setCurrentQuote(data);
      } else if (response.status === 503) {
        // Service unavailable - quote generation failed
        console.error('❌ Service Unavailable (503) - Quote generation failed', {
          status: response.status,
          statusText: response.statusText,
          url: response.url,
          headers: Object.fromEntries(response.headers.entries()),
        });

        try {
          const errorData = await response.json();
          console.error('📋 Error response data:', {
            errorData,
            debugInfo: errorData.debug_info,
            error: errorData.error,
            message: errorData.message,
          });
        } catch (jsonError) {
          console.error('⚠️ Failed to parse error response as JSON:', {
            jsonError: jsonError instanceof Error ? jsonError.message : 'Unknown JSON error',
            responseText: await response.text().catch(() => 'Unable to read response text'),
          });
        }

        setCurrentQuote(null);
      } else {
        console.error('❌ API Error Response (Non-503)', {
          status: response.status,
          statusText: response.statusText,
          url: response.url,
          headers: Object.fromEntries(response.headers.entries()),
        });

        try {
          const errorData = await response.json();
          console.error('📋 Error response data:', errorData);
        } catch (jsonError) {
          console.error('⚠️ Failed to parse error response as JSON:', {
            jsonError: jsonError instanceof Error ? jsonError.message : 'Unknown JSON error',
            responseText: await response.text().catch(() => 'Unable to read response text'),
          });
        }

        throw new Error(`API Error: ${response.status} ${response.statusText}`);
      }
    } catch (error) {
      console.error('💥 Error fetching motivational quote:', {
        error: error,
        message: error instanceof Error ? error.message : 'Unknown error',
        stack: error instanceof Error ? error.stack : undefined,
        name: error instanceof Error ? error.name : 'Unknown',
        requestData: {
          daysCompleted: daysCompleted,
          daysRemaining: daysRemaining,
          percentage: percentage,
        },
        timestamp: new Date().toISOString(),
        userAgent: navigator.userAgent,
        url: window.location.href,
      });

      // Additional debugging for network errors
      if (error instanceof TypeError && error.message.includes('fetch')) {
        console.error('🌐 Network error detected:', {
          message: 'This might be a network connectivity issue or CORS problem',
          error: error.message,
          stack: error.stack,
        });
      }

      // Commented out fallback quote - show message instead
      // setCurrentQuote({
      //   quote: 'Keep going, you\'re doing great!',
      //   type: 'general',
      //   context: 'Stay strong on your journey!',
      // });
      setCurrentQuote(null); // Show message instead of fallback quote
    } finally {
      console.log('🏁 Quote fetch completed', {
        timestamp: new Date().toISOString(),
        isLoadingQuote: false,
      });
      setIsLoadingQuote(false);
    }
  }, [shouldUseFallback]);

  useEffect(() => {
    // Set dates
    const lastSkip = new Date('2025-10-10');
    const targetDate = new Date('2026-02-17'); // Ramadan 2026 start
    const currentDate = new Date();

    // Calculate progress
    const totalDays = Math.ceil((targetDate.getTime() - lastSkip.getTime()) / (1000 * 60 * 60 * 24));
    const daysCompleted = Math.ceil((currentDate.getTime() - lastSkip.getTime()) / (1000 * 60 * 60 * 24));
    const daysRemaining = Math.max(0, totalDays - daysCompleted);
    const percentage = Math.min(100, Math.max(0, (daysCompleted / totalDays) * 100));

    setProgressData({
      lastSkip,
      targetDate,
      currentDate,
      totalDays,
      daysCompleted: Math.max(0, daysCompleted),
      percentage: Math.round(percentage * 10) / 10,
      daysRemaining,
    });

    // Fetch AI-generated motivational quote
    fetchMotivationalQuote(Math.max(0, daysCompleted), daysRemaining, Math.round(percentage * 10) / 10);

    // Check for milestones
    const milestone = milestones.find(m => daysCompleted >= m.days && daysCompleted < m.days + 1);
    if (milestone) {
      setMilestoneMessage(milestone.message);
      setShowMilestone(true);
      setTimeout(() => setShowMilestone(false), 5000);
    }
  }, [fetchMotivationalQuote]);

  if (!progressData) {
    return (
      <Card className="w-full">
        <CardContent className="p-6">
          <div className="animate-pulse">
            <div className="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
            <div className="h-32 bg-gray-200 rounded"></div>
          </div>
        </CardContent>
      </Card>
    );
  }

  const { percentage, daysCompleted, daysRemaining, totalDays } = progressData;

  return (
    <Card className="w-full bg-white dark:bg-gray-900 border-0 shadow-sm">
      <CardContent className="p-8">
        {/* Header */}
        <div className="text-center mb-8">
          <div className="flex items-center justify-center gap-3 mb-4">
            <div className="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
            <h2 className="text-2xl font-semibold text-gray-900 dark:text-white">
              Journey to Ramadan 2026
            </h2>
            <div className="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
          </div>
          
          {/* Motivational Quote */}
          <div className="max-w-2xl mx-auto">
            {isLoadingQuote ? (
              <div className="flex items-center justify-center gap-2 py-4">
                <Loader2 className="h-4 w-4 animate-spin text-emerald-500" />
                <span className="text-gray-500">Generating Quote ...</span>
              </div>
            ) : currentQuote ? (
              <div className="space-y-3">
                <p className="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">
                  "{currentQuote.quote}"
                </p>
                <div className="flex flex-col sm:flex-row items-center justify-center gap-2">
                  <span className="text-sm text-gray-500 dark:text-gray-400">
                    {currentQuote.context}
                  </span>
                  <Badge 
                    variant="outline" 
                    className={`text-xs ${
                      currentQuote.type === 'islamic' 
                        ? 'text-emerald-600 border-emerald-200 bg-emerald-50 dark:bg-emerald-950/20' 
                        : currentQuote.type === 'realistic'
                        ? 'text-slate-600 border-slate-200 bg-slate-50 dark:bg-slate-950/20'
                        : 'text-amber-600 border-amber-200 bg-amber-50 dark:bg-amber-950/20'
                    }`}
                  >
                    {currentQuote.type === 'islamic' ? '🕌 Islamic' : 
                     currentQuote.type === 'realistic' ? '💡 Realistic' : '🌟 General'}
                  </Badge>
                </div>
              </div>
            ) : (
              <div className="py-4 text-center">
                <div className="text-gray-500 dark:text-gray-400 mb-2">
                  Unable to generate motivational quote at this time
                </div>
                <div className="text-sm text-gray-400 dark:text-gray-500 mb-3">
                  Please try again later or continue your journey without a quote
                </div>
                <button
                  onClick={async () => {
                    try {
                      console.log('🔍 Testing debug endpoint...');
                      const response = await fetch('/api/motivational-quote/debug');
                      const debugData = await response.json();
                      console.log('🔍 Debug information:', debugData);
                      alert('Debug info logged to console. Check browser console for details.');
                    } catch (error) {
                      console.error('🔍 Debug endpoint failed:', error);
                      alert('Debug endpoint failed. Check console for details.');
                    }
                  }}
                  className="text-xs px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                >
                  🔍 Debug Info
                </button>
              </div>
            )}
          </div>
        </div>

        {/* Horizontal Progress Bar */}
        <div className="mb-8">
          <div className="flex items-center justify-between mb-2">
            <span className="text-sm font-medium text-gray-600 dark:text-gray-400">Progress</span>
            <span className="text-sm font-semibold text-emerald-600 dark:text-emerald-400">
              {percentage}%
            </span>
          </div>
          <div className="relative">
            <div className="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
              <div 
                className="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full transition-all duration-1000 ease-out relative"
                style={{ width: `${percentage}%` }}
              >
                <div className="absolute right-0 top-0 w-1 h-full bg-white/30 animate-pulse"></div>
              </div>
            </div>
            
            {/* Milestone Markers */}
            <div className="absolute top-0 left-0 w-full h-3 flex items-center">
              {milestones.map((milestone) => {
                const milestonePercentage = (milestone.days / totalDays) * 100;
                const isCompleted = daysCompleted >= milestone.days;
                const isCurrent = daysCompleted >= milestone.days - 3 && daysCompleted < milestone.days + 3;
                
                return (
                  <div
                    key={milestone.days}
                    className="absolute flex flex-col items-center group"
                    style={{ left: `${milestonePercentage}%`, transform: 'translateX(-50%)' }}
                  >
                    {/* Milestone Dot */}
                    <div 
                      className={`w-4 h-4 rounded-full border-2 transition-all duration-300 ${
                        isCompleted 
                          ? `${milestone.color} border-white shadow-lg scale-110` 
                          : isCurrent
                          ? `bg-white ${milestone.color.replace('bg-', 'border-')} border-2 shadow-md scale-105`
                          : 'bg-white border-gray-300 dark:border-gray-600'
                      }`}
                    />
                    
                    {/* Milestone Label */}
                    <div className={`absolute top-5 text-xs font-medium whitespace-nowrap transition-all duration-300 ${
                      isCompleted 
                        ? 'text-gray-900 dark:text-white' 
                        : isCurrent
                        ? 'text-gray-700 dark:text-gray-300'
                        : 'text-gray-500 dark:text-gray-400'
                    }`}>
                      {milestone.days}d
                    </div>
                    
                    {/* Tooltip on Hover */}
                    <div className="absolute bottom-8 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-10">
                      <div className="bg-gray-900 dark:bg-gray-700 text-white text-xs px-2 py-1 rounded shadow-lg whitespace-nowrap">
                        {milestone.message}
                      </div>
                      <div className="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-2 border-r-2 border-t-2 border-transparent border-t-gray-900 dark:border-t-gray-700"></div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </div>

        {/* Stats Grid */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
          <div className="text-center group">
            <div className="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-1 group-hover:scale-105 transition-transform duration-200">
              {daysCompleted}
            </div>
            <div className="text-sm text-gray-600 dark:text-gray-400 font-medium">
              Days Strong
            </div>
          </div>
          
          <div className="text-center group">
            <div className="text-3xl font-bold text-slate-600 dark:text-slate-400 mb-1 group-hover:scale-105 transition-transform duration-200">
              {daysRemaining}
            </div>
            <div className="text-sm text-gray-600 dark:text-gray-400 font-medium">
              Days to Go
            </div>
          </div>
          
          <div className="text-center group">
            <div className="text-3xl font-bold text-amber-600 dark:text-amber-400 mb-1 group-hover:scale-105 transition-transform duration-200">
              {totalDays}
            </div>
            <div className="text-sm text-gray-600 dark:text-gray-400 font-medium">
              Total Journey
            </div>
          </div>
          
          <div className="text-center group">
            <div className="text-3xl font-bold text-rose-600 dark:text-rose-400 mb-1 group-hover:scale-105 transition-transform duration-200">
              {Math.round((daysCompleted / 7))}
            </div>
            <div className="text-sm text-gray-600 dark:text-gray-400 font-medium">
              Weeks Strong
            </div>
          </div>
        </div>

        {/* Timeline */}
        <div className="border-t border-gray-200 dark:border-gray-700 pt-6">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
              <div className="w-8 h-8 bg-rose-100 dark:bg-rose-900/20 rounded-full flex items-center justify-center">
                <Calendar className="h-4 w-4 text-rose-600 dark:text-rose-400" />
              </div>
              <div>
                <div className="text-sm font-medium text-gray-900 dark:text-white">Count Started On</div>
                <div className="text-xs text-gray-500 dark:text-gray-400">Oct 9, 2025</div>
              </div>
            </div>
            
            <div className="flex items-center gap-3">
              <div className="w-8 h-8 bg-emerald-100 dark:bg-emerald-900/20 rounded-full flex items-center justify-center">
                <Target className="h-4 w-4 text-emerald-600 dark:text-emerald-400" />
              </div>
              <div className="text-right">
                <div className="text-sm font-medium text-gray-900 dark:text-white">Ramadan 2026</div>
                <div className="text-xs text-gray-500 dark:text-gray-400">Feb 17, 2026</div>
              </div>
            </div>
          </div>
        </div>

        {/* Milestone Celebration */}
        {showMilestone && (
          <div className="fixed top-4 right-4 z-50 animate-bounce">
            <div className="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-3 rounded-full shadow-lg flex items-center gap-2">
              <Sparkles className="h-5 w-5" />
              <span className="font-semibold">{milestoneMessage}</span>
            </div>
          </div>
        )}
      </CardContent>
    </Card>
  );
}
