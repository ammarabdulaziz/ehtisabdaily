import LinkPasswordController from '@/actions/App/Http/Controllers/Auth/LinkPasswordController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';
import { dashboard } from '@/routes';
import { Form, Head } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';

export default function LinkPassword() {
    return (
        <AuthLayout 
            title="Link a Password" 
            description="You can optionally link a password to your account for additional security"
        >
            <Head title="Link Password" />

            <div className="space-y-6">
                <div className="text-center text-sm text-muted-foreground">
                    <p>
                        You signed in with Google! You can optionally link a password to your account 
                        for additional security, or skip this step and continue to your dashboard.
                    </p>
                </div>

                <Form
                    {...LinkPasswordController.store.form()}
                    resetOnSuccess={['password', 'password_confirmation']}
                    className="flex flex-col gap-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-6">
                                <div className="grid gap-2">
                                    <Label htmlFor="password">Password</Label>
                                    <Input
                                        id="password"
                                        type="password"
                                        name="password"
                                        required
                                        autoFocus
                                        tabIndex={1}
                                        autoComplete="new-password"
                                        placeholder="Enter a password"
                                    />
                                    <InputError message={errors.password} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="password_confirmation">Confirm Password</Label>
                                    <Input
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        required
                                        tabIndex={2}
                                        autoComplete="new-password"
                                        placeholder="Confirm your password"
                                    />
                                    <InputError message={errors.password_confirmation} />
                                </div>

                                <Button type="submit" className="mt-4 w-full" tabIndex={3} disabled={processing}>
                                    {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                                    Link Password
                                </Button>
                            </div>
                        </>
                    )}
                </Form>

                <div className="text-center">
                    <Form action={LinkPasswordController.skip} method="post">
                        <Button type="submit" variant="ghost" className="w-full" tabIndex={4}>
                            Skip for now
                        </Button>
                    </Form>
                </div>

                <div className="text-center text-sm text-muted-foreground">
                    <p>
                        You can always link a password later in your account settings.
                    </p>
                </div>
            </div>
        </AuthLayout>
    );
}
