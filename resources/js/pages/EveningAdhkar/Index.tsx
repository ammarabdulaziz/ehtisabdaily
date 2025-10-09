import { Head } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from '@/components/ui/carousel'
import { BookOpen, Heart, Moon } from 'lucide-react'
import eveningAdhkarData from '@/data/evening.json'

interface AdhkarItem {
  id: number;
  title: string;
  arabic_text: string;
  transliteration?: string;
  english_translation?: string;
  english_meaning?: string;
  categories: string[];
  source?: string;
  reference?: string;
  benefits?: string;
  recitation_count: number;
  user: {
    id: number;
    name: string;
  };
}

const eveningAdhkar: AdhkarItem[] = eveningAdhkarData.map(item => ({
  ...item,
  user: {
    id: 1,
    name: "System"
  }
}))

export default function EveningAdhkarIndex() {

  return (
    <AppLayout>
      <Head title="Evening Adhkar" />
      
      <div className="container mx-auto px-4 py-8">
        {/* Header */}
        <div className="mb-8 text-center">
          <h1 className="text-4xl font-bold text-foreground mb-4">
            Evening Adhkar
          </h1>
          <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
            Daily evening remembrances and supplications to end your day with Allah's blessings.
          </p>
        </div>

        {/* Adhkar Carousel */}
        {eveningAdhkar.length > 0 ? (
          <div className="max-w-8xl mx-auto">
            <Carousel
              opts={{
                align: "start",
                loop: false,
              }}
              className="w-full"
            >
              <CarouselContent className="-ml-2 md:-ml-4">
                {eveningAdhkar.map((adhkar) => (
                  <CarouselItem key={adhkar.id} className="pl-2 md:pl-4 md:basis-1/2 lg:basis-1/3">
                    <AdhkarCard adhkar={adhkar} />
                  </CarouselItem>
                ))}
              </CarouselContent>
              <CarouselPrevious className="hidden md:flex" />
              <CarouselNext className="hidden md:flex" />
            </Carousel>
          </div>
        ) : (
          <div className="text-center py-12">
            <Moon className="mx-auto h-12 w-12 text-muted-foreground mb-4" />
            <h3 className="text-lg font-semibold mb-2">No adhkar found</h3>
            <p className="text-muted-foreground">
              No adhkar available.
            </p>
          </div>
        )}

        {/* Stats */}
        <div className="mt-12 flex justify-center gap-20 max-w-2xl mx-auto">
          <div className="text-center">
            <div className="text-3xl font-bold text-primary">{eveningAdhkar.length}</div>
            <div className="text-sm text-muted-foreground">Total Adhkar</div>
          </div>
        </div>
      </div>
    </AppLayout>
  )
}

function AdhkarCard({ adhkar }: { adhkar: AdhkarItem }) {
  return (
    <Card className="h-full flex flex-col hover:shadow-lg transition-shadow duration-200">
      <CardHeader className="pb-4">
        <div className="flex items-start justify-between">
          <div className="flex-1">
            <CardTitle className="text-lg leading-tight mb-2">
              {adhkar.title}
            </CardTitle>
            <CardDescription className="text-sm">
              {adhkar.source && (
                <span className="inline-flex items-center gap-1">
                  <BookOpen className="h-3 w-3" />
                  {adhkar.source}
                </span>
              )}
            </CardDescription>
          </div>
          <Badge variant="secondary" className="ml-2">
            {adhkar.recitation_count === 1 ? 'Once' : `${adhkar.recitation_count} times`}
          </Badge>
        </div>
      </CardHeader>

      <CardContent className="flex-1 flex flex-col">
        {/* Arabic Text */}
        <div className="mb-4">
          <div dir="rtl" className="text-right border text-xl leading-loose font-arabic bg-muted/50 p-4 pt-5 pb-3 rounded-lg" style={{ fontFamily: 'Amiri, Scheherazade New, Traditional Arabic, Arabic Typesetting, serif' }}>
            {adhkar.arabic_text}
          </div>
        </div>

        {/* Transliteration */}
        {adhkar.transliteration && (
          <div className="mb-3">
            <p className="text-sm text-muted-foreground italic">
              {adhkar.transliteration}
            </p>
          </div>
        )}

        {/* English Translation */}
        {adhkar.english_translation && (
          <div className="mb-3">
            <p className="text-sm leading-relaxed">
              {adhkar.english_translation}
            </p>
          </div>
        )}

        {/* English Meaning */}
        {adhkar.english_meaning && (
          <div className="mb-4">
            <p className="text-xs text-muted-foreground">
              <strong>Meaning:</strong> {adhkar.english_meaning}
            </p>
          </div>
        )}

        {/* Categories */}
        <div className="mb-4">
          <div className="flex flex-wrap gap-1">
            {adhkar.categories.slice(0, 2).map((category) => (
              <Badge key={category} variant="outline" className="text-xs">
                {category}
              </Badge>
            ))}
            {adhkar.categories.length > 2 && (
              <Badge variant="outline" className="text-xs">
                +{adhkar.categories.length - 2} more
              </Badge>
            )}
          </div>
        </div>

        {/* Benefits */}
        {adhkar.benefits && (
          <div className="mb-4 flex-1">
            <div className="flex items-start gap-2">
              <Heart className="h-4 w-4 text-red-500 mt-0.5 flex-shrink-0" />
              <p className="text-xs text-muted-foreground leading-relaxed">
                {adhkar.benefits}
              </p>
            </div>
          </div>
        )}

        {/* Reference */}
        {adhkar.reference && (
          <div className="mt-auto pt-2 border-t">
            <p className="text-xs text-muted-foreground">
              <strong>Reference:</strong> {adhkar.reference}
            </p>
          </div>
        )}
      </CardContent>
    </Card>
  )
}
