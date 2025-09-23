import { Head } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout'

export default function AssetsIndex() {
  return (
    <AppLayout>
      <Head title="Assets" />
      
      <div className="container mx-auto px-4 py-8">
        <h1 className="text-2xl font-bold">Assets</h1>
      </div>
    </AppLayout>
  )
}
