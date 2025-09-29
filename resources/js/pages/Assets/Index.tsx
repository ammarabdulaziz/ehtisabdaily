import { Head } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { ChartContainer, ChartTooltip, ChartTooltipContent, ChartLegend, ChartLegendContent, type ChartConfig } from '@/components/ui/chart'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Badge } from '@/components/ui/badge'
import { Skeleton } from '@/components/ui/skeleton'
import { LineChart, Line, XAxis, YAxis, CartesianGrid, ResponsiveContainer, PieChart, Pie, Cell, BarChart, Bar } from 'recharts'
import { useState, useEffect } from 'react'
import { TrendingUp, TrendingDown, Wallet, PiggyBank, CreditCard, Users, BarChart3, PieChart as PieChartIcon } from 'lucide-react'

interface ChartDataPoint {
  period: string
  month: string
  year: number
  totalAccounts: number
  totalLentMoney: number
  totalBorrowedMoney: number
  totalInvestments: number
  totalDeposits: number
  grandTotal: number
  savings: number
}

interface AllocationData {
  name: string
  value: number
  percentage: number
  type: 'asset' | 'liability'
}

interface ChartData {
  chartData: ChartDataPoint[]
  summary: {
    totalPeriods: number
    currentNetWorth: number
    totalSavings: number
    averageMonthlySavings: number
    peakNetWorth: number
    lowestNetWorth: number
  }
  timeRange: {
    years: number
    startDate: string
    endDate: string
  }
}

interface AllocationResponse {
  allocations: AllocationData[]
  total: number
  period: string
  netWorth: number
}

const chartConfig = {
  totalAccounts: {
    label: "Accounts",
    color: "#2b8474",
  },
  totalInvestments: {
    label: "Investments", 
    color: "#059669",
  },
  totalDeposits: {
    label: "Deposits",
    color: "#0891b2",
  },
  totalLentMoney: {
    label: "Lent Money",
    color: "#7c3aed",
  },
  totalBorrowedMoney: {
    label: "Borrowed Money",
    color: "#dc2626",
  },
  grandTotal: {
    label: "Net Worth",
    color: "#2b8474",
  },
  savings: {
    label: "Monthly Savings",
    color: "#16a34a",
  },
} satisfies ChartConfig

const allocationColors = [
  "#2b8474", // Brand color
  "#059669", // Green
  "#0891b2", // Blue
  "#7c3aed", // Purple
  "#dc2626", // Red
]

export default function AssetsIndex() {
  const [chartData, setChartData] = useState<ChartData | null>(null)
  const [allocationData, setAllocationData] = useState<AllocationResponse | null>(null)
  const [loading, setLoading] = useState(true)
  const [timeRange, setTimeRange] = useState(2)

  const fetchChartData = async (years: number) => {
    try {
      setLoading(true)
      const response = await fetch(`/api/assets/chart-data?years=${years}`)
      const data = await response.json()
      setChartData(data)
    } catch (error) {
      console.error('Error fetching chart data:', error)
    } finally {
      setLoading(false)
    }
  }

  const fetchAllocationData = async () => {
    try {
      const response = await fetch('/api/assets/allocation-breakdown')
      const data = await response.json()
      setAllocationData(data)
    } catch (error) {
      console.error('Error fetching allocation data:', error)
    }
  }

  useEffect(() => {
    fetchChartData(timeRange)
    fetchAllocationData()
  }, [timeRange])

  const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('en-QA', {
      style: 'currency',
      currency: 'QAR',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(value)
  }

  const formatTooltipValue = (value: number) => {
    return formatCurrency(value)
  }

  const formatXAxisLabel = (tickItem: string) => {
    const [year, month] = tickItem.split('-')
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    return `${monthNames[parseInt(month) - 1]} ${year.slice(2)}`
  }

  if (loading) {
    return (
      <AppLayout>
        <Head title="Assets Dashboard" />
        <div className="container mx-auto px-4 py-8 space-y-6">
          <div className="flex items-center justify-between">
            <h1 className="text-3xl font-bold">Assets Dashboard</h1>
            <Skeleton className="h-10 w-32" />
          </div>
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            {[...Array(4)].map((_, i) => (
              <Card key={i}>
                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                  <Skeleton className="h-4 w-24" />
                  <Skeleton className="h-4 w-4" />
                </CardHeader>
                <CardContent>
                  <Skeleton className="h-8 w-32 mb-2" />
                  <Skeleton className="h-3 w-20" />
                </CardContent>
              </Card>
            ))}
          </div>
          <div className="grid gap-6 lg:grid-cols-2">
            <Card>
              <CardHeader>
                <Skeleton className="h-6 w-48" />
              </CardHeader>
              <CardContent>
                <Skeleton className="h-80 w-full" />
              </CardContent>
            </Card>
            <Card>
              <CardHeader>
                <Skeleton className="h-6 w-48" />
              </CardHeader>
              <CardContent>
                <Skeleton className="h-80 w-full" />
              </CardContent>
            </Card>
          </div>
        </div>
      </AppLayout>
    )
  }

  return (
    <AppLayout>
      <Head title="Assets Dashboard" />
      
      <div className="container mx-auto px-4 py-8 space-y-6">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold">Assets Dashboard</h1>
            <p className="text-muted-foreground">
              Track your financial growth and asset allocation over time
            </p>
          </div>
          <div className="flex items-center gap-4">
            <Select value={timeRange.toString()} onValueChange={(value) => setTimeRange(parseInt(value))}>
              <SelectTrigger className="w-32">
                <SelectValue placeholder="Time Range" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="1">Last Year</SelectItem>
                <SelectItem value="2">Last 2 Years</SelectItem>
                <SelectItem value="3">Last 3 Years</SelectItem>
                <SelectItem value="5">Last 5 Years</SelectItem>
              </SelectContent>
            </Select>
          </div>
        </div>

        {/* Summary Cards */}
        {chartData && (
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <Card>
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">Current Net Worth</CardTitle>
                <Wallet className="h-4 w-4 text-muted-foreground" />
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{formatCurrency(chartData.summary.currentNetWorth)}</div>
                <p className="text-xs text-muted-foreground">
                  {chartData.summary.totalPeriods} periods tracked
                </p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">Total Savings</CardTitle>
                <PiggyBank className="h-4 w-4 text-muted-foreground" />
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{formatCurrency(chartData.summary.totalSavings)}</div>
                <p className="text-xs text-muted-foreground">
                  Avg: {formatCurrency(chartData.summary.averageMonthlySavings)}/month
                </p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">Peak Net Worth</CardTitle>
                <TrendingUp className="h-4 w-4 text-green-600" />
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{formatCurrency(chartData.summary.peakNetWorth)}</div>
                <p className="text-xs text-muted-foreground">
                  Highest recorded value
                </p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">Lowest Net Worth</CardTitle>
                <TrendingDown className="h-4 w-4 text-red-600" />
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{formatCurrency(chartData.summary.lowestNetWorth)}</div>
                <p className="text-xs text-muted-foreground">
                  Lowest recorded value
                </p>
              </CardContent>
            </Card>
          </div>
        )}

        {/* Charts */}
        <div className="grid gap-6 lg:grid-cols-2">
          {/* Multi-line Net Trend Chart */}
          <Card className="col-span-2">
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <BarChart3 className="h-5 w-5" />
                Asset Allocation Trends
              </CardTitle>
              <CardDescription>
                Track your net worth and asset allocation over time
              </CardDescription>
            </CardHeader>
            <CardContent>
              {chartData && chartData.chartData.length > 0 ? (
                <ChartContainer config={chartConfig} className="h-96 w-full">
                  <LineChart data={chartData.chartData}>
                    <CartesianGrid strokeDasharray="3 3" />
                    <XAxis 
                      dataKey="period" 
                      tickFormatter={formatXAxisLabel}
                      tick={{ fontSize: 12 }}
                    />
                    <YAxis 
                      tickFormatter={(value) => formatCurrency(value)}
                      tick={{ fontSize: 12 }}
                    />
                    <ChartTooltip 
                      content={<ChartTooltipContent formatter={formatTooltipValue} />}
                    />
                    <ChartLegend content={<ChartLegendContent />} />
                    <Line 
                      type="monotone" 
                      dataKey="grandTotal" 
                      stroke="var(--color-grandTotal)" 
                      strokeWidth={3}
                      dot={{ r: 4 }}
                      activeDot={{ r: 6 }}
                    />
                    <Line 
                      type="monotone" 
                      dataKey="totalAccounts" 
                      stroke="var(--color-totalAccounts)" 
                      strokeWidth={2}
                      dot={{ r: 3 }}
                    />
                    <Line 
                      type="monotone" 
                      dataKey="totalInvestments" 
                      stroke="var(--color-totalInvestments)" 
                      strokeWidth={2}
                      dot={{ r: 3 }}
                    />
                    <Line 
                      type="monotone" 
                      dataKey="totalDeposits" 
                      stroke="var(--color-totalDeposits)" 
                      strokeWidth={2}
                      dot={{ r: 3 }}
                    />
                    <Line 
                      type="monotone" 
                      dataKey="totalLentMoney" 
                      stroke="var(--color-totalLentMoney)" 
                      strokeWidth={2}
                      dot={{ r: 3 }}
                    />
                    <Line 
                      type="monotone" 
                      dataKey="totalBorrowedMoney" 
                      stroke="var(--color-totalBorrowedMoney)" 
                      strokeWidth={2}
                      dot={{ r: 3 }}
                    />
                  </LineChart>
                </ChartContainer>
              ) : (
                <div className="flex h-96 items-center justify-center text-muted-foreground">
                  <div className="text-center">
                    <BarChart3 className="h-12 w-12 mx-auto mb-4 opacity-50" />
                    <p>No data available for the selected time range</p>
                  </div>
                </div>
              )}
            </CardContent>
          </Card>

          {/* Asset Allocation Pie Chart */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <PieChartIcon className="h-5 w-5" />
                Current Asset Allocation
              </CardTitle>
              <CardDescription>
                {allocationData?.period && `As of ${allocationData.period}`}
              </CardDescription>
            </CardHeader>
            <CardContent>
              {allocationData && allocationData.allocations.length > 0 ? (
                <ChartContainer config={chartConfig} className="h-80">
                  <PieChart>
                    <Pie
                      data={allocationData.allocations}
                      cx="50%"
                      cy="50%"
                      labelLine={false}
                      label={({ name, percentage }) => `${name}: ${percentage}%`}
                      outerRadius={100}
                      fill="#8884d8"
                      dataKey="value"
                    >
                      {allocationData.allocations.map((entry, index) => (
                        <Cell key={`cell-${index}`} fill={allocationColors[index % allocationColors.length]} />
                      ))}
                    </Pie>
                    <ChartTooltip 
                      content={<ChartTooltipContent formatter={formatTooltipValue} />}
                    />
                  </PieChart>
                </ChartContainer>
              ) : (
                <div className="flex h-80 items-center justify-center text-muted-foreground">
                  <div className="text-center">
                    <PieChartIcon className="h-12 w-12 mx-auto mb-4 opacity-50" />
                    <p>No allocation data available</p>
                  </div>
                </div>
              )}
            </CardContent>
          </Card>

          {/* Monthly Savings Bar Chart */}
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <TrendingUp className="h-5 w-5" />
                Monthly Savings Trend
              </CardTitle>
              <CardDescription>
                Track your monthly savings over time
              </CardDescription>
            </CardHeader>
            <CardContent>
              {chartData && chartData.chartData.length > 0 ? (
                <ChartContainer config={chartConfig} className="h-80">
                  <BarChart data={chartData.chartData}>
                    <CartesianGrid strokeDasharray="3 3" />
                    <XAxis 
                      dataKey="period" 
                      tickFormatter={formatXAxisLabel}
                      tick={{ fontSize: 12 }}
                    />
                    <YAxis 
                      tickFormatter={(value) => formatCurrency(value)}
                      tick={{ fontSize: 12 }}
                    />
                    <ChartTooltip 
                      content={<ChartTooltipContent formatter={formatTooltipValue} />}
                    />
                    <Bar 
                      dataKey="savings" 
                      fill="var(--color-savings)"
                      radius={[4, 4, 0, 0]}
                    />
                  </BarChart>
                </ChartContainer>
              ) : (
                <div className="flex h-80 items-center justify-center text-muted-foreground">
                  <div className="text-center">
                    <TrendingUp className="h-12 w-12 mx-auto mb-4 opacity-50" />
                    <p>No savings data available</p>
                  </div>
                </div>
              )}
            </CardContent>
          </Card>
        </div>

        {/* Allocation Details */}
        {allocationData && allocationData.allocations.length > 0 && (
          <Card>
            <CardHeader>
              <CardTitle>Asset Allocation Details</CardTitle>
              <CardDescription>
                Breakdown of your current financial position
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                {allocationData.allocations.map((allocation, index) => (
                  <div key={allocation.name} className="flex items-center justify-between p-4 border rounded-lg">
                    <div className="flex items-center gap-3">
                      <div 
                        className="w-4 h-4 rounded-full" 
                        style={{ backgroundColor: allocationColors[index % allocationColors.length] }}
                      />
                      <div>
                        <p className="font-medium">{allocation.name}</p>
                        <p className="text-sm text-muted-foreground">{allocation.percentage}%</p>
                      </div>
                    </div>
                    <div className="text-right">
                      <p className="font-medium">{formatCurrency(allocation.value)}</p>
                      <Badge variant={allocation.type === 'asset' ? 'default' : 'destructive'}>
                        {allocation.type}
                      </Badge>
                    </div>
                  </div>
                ))}
              </div>
            </CardContent>
          </Card>
        )}
      </div>
    </AppLayout>
  )
}

