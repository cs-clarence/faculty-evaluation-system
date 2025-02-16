import {
    Livewire,
    Alpine,
} from "../../vendor/livewire/livewire/dist/livewire.esm";

type Data = {
    edit: boolean;
    text: string;
    savedText: string;
    required: boolean;
};

type DispatchFn = (eventType: string, detail: unknown) => void;

document.addEventListener("alpine:init", () => {
    Alpine.data("editableText", (data: Data) => ({
        ...data,
        get unchanged(): boolean {
            return this.text === this.savedText;
        },
        save(event: Event, dispatch: DispatchFn) {
            if (this.text.trim() !== "" || !this.required) {
                this.edit = false;
                if (this.text !== this.savedText) {
                    this.savedText = this.text;
                    dispatch("save", {
                        text: this.text,
                    });
                } else {
                    event.stopPropagation();
                }
            }
        },
        cancel(event: Event) {
            if (
                !this.unchanged &&
                !confirm(
                    "You have unsaved changes. Are you sure you want to drop them?",
                )
            ) {
                event.stopPropagation();
                event.preventDefault();
                return;
            }
            this.edit = false;
            this.text = this.savedText;
        },
        get error(): boolean {
            return !this.text.trim() && this.required;
        },
        get rows(): number {
            return this.text.split("\n").length;
        },
    }));
});

Livewire.start();
