import { Head, router } from '@inertiajs/react'
import { useState } from 'react'
import AppLayout from '@/layouts/app-layout'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { ToggleGroup, ToggleGroupItem } from '@/components/ui/toggle-group'
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'
import { Button } from '@/components/ui/button'
import { BookOpen, Heart, ChevronDown, ChevronUp } from 'lucide-react'

interface Dua {
  id: number
  title: string
  arabic_text: string
  transliteration?: string
  english_translation?: string
  english_meaning?: string
  categories: string[]
  source?: string
  reference?: string
  benefits?: string
  recitation_count: number
  user: {
    id: number
    name: string
  }
}

interface Props {
  duas: Dua[]
  categories: Record<string, string>
  currentCategory?: string
}

export default function DuasIndex({ duas, categories, currentCategory }: Props) {
  // Find the category with the most duas
  const getCategoryWithMostDuas = () => {
    const categoryCounts: Record<string, number> = {}
    
    duas.forEach(dua => {
      dua.categories.forEach(category => {
        categoryCounts[category] = (categoryCounts[category] || 0) + 1
      })
    })
    
    // Find the category with the highest count
    const mostPopularCategory = Object.entries(categoryCounts).reduce((a, b) => 
      categoryCounts[a[0]] > categoryCounts[b[0]] ? a : b
    )
    
    return mostPopularCategory ? mostPopularCategory[0] : 'all'
  }

  const [selectedCategory, setSelectedCategory] = useState<string>(
    currentCategory || getCategoryWithMostDuas()
  )
  const [isCategoriesExpanded, setIsCategoriesExpanded] = useState<boolean>(false)

  const handleCategoryChange = (category: string) => {
    setSelectedCategory(category)
    if (category === 'all') {
      router.get('/duas')
    } else {
      router.get('/duas', { category })
    }
  }

  const filteredDuas = selectedCategory === 'all' 
    ? duas 
    : duas.filter(dua => dua.categories.includes(selectedCategory))

  return (
    <AppLayout>
      <Head title="Duas" />
      
      <div className="container mx-auto px-4 py-8">
        {/* Header */}
        <div className="mb-8">
          {/* Mobile: Title and Filter Button in same row */}
          <div className="md:hidden flex items-center justify-between mb-4">
            <h1 className="text-2xl font-bold text-foreground">
              Dua Collection
            </h1>
            <Button
              variant="outline"
              onClick={() => setIsCategoriesExpanded(!isCategoriesExpanded)}
              className="flex items-center gap-2"
            >
              Categories
              {isCategoriesExpanded ? (
                <ChevronUp className="h-4 w-4" />
              ) : (
                <ChevronDown className="h-4 w-4" />
              )}
            </Button>
          </div>

          {/* Desktop: Centered title */}
          <div className="hidden md:block text-center">
            <h1 className="text-4xl font-bold text-foreground mb-4">
              Dua Collection
            </h1>
            {/* <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
              Discover beautiful duas for every occasion. Swipe through our collection of authentic Islamic supplications.
            </p> */}
          </div>
        </div>

        {/* Category Filters */}
        <div className="mb-8">

          {/* Desktop: Always visible, Mobile: Collapsible */}
          <div className={`${isCategoriesExpanded ? 'block' : 'hidden'} md:block`}>
            <div className="flex justify-center">
              <ToggleGroup
                type="single"
                value={selectedCategory}
                onValueChange={handleCategoryChange}
                variant="outline"
                className="flex-wrap gap-2 max-w-8xl"
              >
                <ToggleGroupItem value="all" className="px-4 py-2">
                  All Duas
                </ToggleGroupItem>
                {Object.entries(categories).map(([key, label]) => (
                  <ToggleGroupItem key={key} value={key} className="px-4 py-2">
                    {label}
                  </ToggleGroupItem>
                ))}
              </ToggleGroup>
            </div>
          </div>
        </div>

        {/* Duas Carousel */}
        {filteredDuas.length > 0 ? (
          <div className="max-w-8xl mx-auto">
            <Carousel
              opts={{
                align: "start",
                loop: false,
              }}
              className="w-full"
            >
              <CarouselContent className="-ml-2 md:-ml-4">
                {filteredDuas.map((dua) => (
                  <CarouselItem key={dua.id} className="pl-2 md:pl-4 md:basis-1/2 lg:basis-1/3">
                    <DuaCard dua={dua} />
                  </CarouselItem>
                ))}
              </CarouselContent>
              <CarouselPrevious className="hidden md:flex" />
              <CarouselNext className="hidden md:flex" />
            </Carousel>
          </div>
        ) : (
          <div className="text-center py-12">
            <BookOpen className="mx-auto h-12 w-12 text-muted-foreground mb-4" />
            <h3 className="text-lg font-semibold mb-2">No duas found</h3>
            <p className="text-muted-foreground">
              No duas available for the selected category.
            </p>
          </div>
        )}

        {/* Stats */}
        <div className="mt-12 flex justify-center gap-20 max-w-2xl mx-auto">
          <div className="text-center">
            <div className="text-3xl font-bold text-primary">{duas.length}</div>
            <div className="text-sm text-muted-foreground">Total Duas</div>
          </div>
          <div className="text-center">
            <div className="text-3xl font-bold text-primary">{Object.keys(categories).length}</div>
            <div className="text-sm text-muted-foreground">Categories</div>
          </div>
          <div className="text-center">
            <div className="text-3xl font-bold text-primary">{filteredDuas.length}</div>
            <div className="text-sm text-muted-foreground">Showing</div>
          </div>
        </div>
      </div>
    </AppLayout>
  )
}

function DuaCard({ dua }: { dua: Dua }) {
  return (
    <Card className="h-full flex flex-col hover:shadow-lg transition-shadow duration-200">
      <CardHeader className="pb-4">
        <div className="flex items-start justify-between">
          <div className="flex-1">
            <CardTitle className="text-lg leading-tight mb-2">
              {dua.title}
            </CardTitle>
            <CardDescription className="text-sm">
              {dua.source && (
                <span className="inline-flex items-center gap-1">
                  <BookOpen className="h-3 w-3" />
                  {dua.source}
                </span>
              )}
            </CardDescription>
          </div>
          <Badge variant="secondary" className="ml-2">
            {dua.recitation_count === 1 ? 'Once' : `${dua.recitation_count} times`}
          </Badge>
        </div>
      </CardHeader>

      <CardContent className="flex-1 flex flex-col">
        {/* Arabic Text */}
        <div className="mb-4">
          <div dir="rtl" className="text-right border text-xl leading-loose font-arabic bg-muted/50 p-4 pt-5 pb-3 rounded-lg" style={{ fontFamily: 'Amiri, Scheherazade New, Traditional Arabic, Arabic Typesetting, serif' }}>
            {dua.arabic_text}
          </div>
        </div>

        {/* Transliteration */}
        {dua.transliteration && (
          <div className="mb-3">
            <p className="text-sm text-muted-foreground italic">
              {dua.transliteration}
            </p>
          </div>
        )}

        {/* English Translation */}
        {dua.english_translation && (
          <div className="mb-3">
            <p className="text-sm leading-relaxed">
              {dua.english_translation}
            </p>
          </div>
        )}

        {/* English Meaning */}
        {dua.english_meaning && (
          <div className="mb-4">
            <p className="text-xs text-muted-foreground">
              <strong>Meaning:</strong> {dua.english_meaning}
            </p>
          </div>
        )}

        {/* Categories */}
        <div className="mb-4">
          <div className="flex flex-wrap gap-1">
            {dua.categories.slice(0, 2).map((category) => (
              <Badge key={category} variant="outline" className="text-xs">
                {category}
              </Badge>
            ))}
            {dua.categories.length > 2 && (
              <Badge variant="outline" className="text-xs">
                +{dua.categories.length - 2} more
              </Badge>
            )}
          </div>
        </div>

        {/* Benefits */}
        {dua.benefits && (
          <div className="mb-4 flex-1">
            <div className="flex items-start gap-2">
              <Heart className="h-4 w-4 text-red-500 mt-0.5 flex-shrink-0" />
              <p className="text-xs text-muted-foreground leading-relaxed">
                {dua.benefits}
              </p>
            </div>
          </div>
        )}

        {/* Reference */}
        {dua.reference && (
          <div className="mt-auto pt-2 border-t">
            <p className="text-xs text-muted-foreground">
              <strong>Reference:</strong> {dua.reference}
            </p>
          </div>
        )}
      </CardContent>
    </Card>
  )
}
